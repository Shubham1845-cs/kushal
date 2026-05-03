const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const mysql = require('mysql');
const path = require('path');

const app = express();
const PORT = 3000;

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));

// MySQL Database Connection
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'student_db'
});

connection.connect((err) => {
    if (err) {
        console.log('Database connection error:', err);
        // Try to create database if it doesn't exist
        const tempConnection = mysql.createConnection({
            host: 'localhost',
            user: 'root',
            password: ''
        });

        tempConnection.connect((err) => {
            if (!err) {
                tempConnection.query('CREATE DATABASE IF NOT EXISTS student_db', (err) => {
                    if (!err) {
                        console.log('Database created');
                        connection.changeUser({ database: 'student_db' }, (err) => {
                            if (!err) console.log('Connected to student_db');
                        });
                    }
                });
            }
            tempConnection.end();
        });
    } else {
        console.log('Connected to MySQL');
        
        // Create students table if it doesn't exist
        const createTableSQL = `
            CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                course VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `;
        
        connection.query(createTableSQL, (err) => {
            if (err) console.log('Error creating table:', err);
            else console.log('Students table ready');
        });
    }
});

// ROUTES

// Get all students - returns HTML page when visited in browser, JSON when requested via API
app.get('/api/students', (req, res) => {
    const query = 'SELECT * FROM students ORDER BY created_at DESC';
    connection.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });

        // If request accepts HTML (browser visit), return a styled HTML page
        if (req.headers.accept && req.headers.accept.includes('text/html')) {
            const rows = results.length === 0
                ? `<tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">No students registered yet</td></tr>`
                : results.map(s => `
                    <tr>
                        <td>${s.id}</td>
                        <td>${s.name}</td>
                        <td>${s.email}</td>
                        <td>${s.course}</td>
                        <td>${new Date(s.created_at).toLocaleString()}</td>
                    </tr>`).join('');

            return res.send(`
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Student List</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 30px; }
                        .container { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                        h1 { color: #333; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background: #28a745; color: white; }
                        tr:hover { background: #f5f5f5; }
                        .count { color: #666; font-size: 14px; margin-bottom: 10px; }
                        .back { display: inline-block; margin-bottom: 15px; color: #28a745; text-decoration: none; font-weight: bold; }
                        .back:hover { text-decoration: underline; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <a class="back" href="/">← Back to Registration</a>
                        <h1>👨‍🎓 Student List</h1>
                        <p class="count">Total students: <strong>${results.length}</strong></p>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Registered On</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                </body>
                </html>
            `);
        }

        // Otherwise return JSON (for API/Postman calls)
        res.json(results);
    });
});

// Get student by ID
app.get('/api/students/:id', (req, res) => {
    const query = 'SELECT * FROM students WHERE id = ?';
    connection.query(query, [req.params.id], (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        if (results.length === 0) return res.status(404).json({ error: 'Student not found' });
        res.json(results[0]);
    });
});

// Create new student
app.post('/api/students', (req, res) => {
    const { name, email, course } = req.body;

    if (!name || !email || !course) {
        return res.status(400).json({ error: 'All fields are required' });
    }

    const query = 'INSERT INTO students (name, email, course) VALUES (?, ?, ?)';
    connection.query(query, [name, email, course], (err, results) => {
        if (err) {
            if (err.code === 'ER_DUP_ENTRY') {
                return res.status(400).json({ error: 'Email already registered' });
            }
            return res.status(500).json({ error: err.message });
        }
        res.status(201).json({ id: results.insertId, name, email, course });
    });
});

// Update student
app.put('/api/students/:id', (req, res) => {
    const { name, email, course } = req.body;
    const query = 'UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?';
    
    connection.query(query, [name, email, course, req.params.id], (err, results) => {
        if (err) {
            if (err.code === 'ER_DUP_ENTRY') {
                return res.status(400).json({ error: 'Email already registered' });
            }
            return res.status(500).json({ error: err.message });
        }
        if (results.affectedRows === 0) return res.status(404).json({ error: 'Student not found' });
        res.json({ message: 'Student updated', id: req.params.id, name, email, course });
    });
});

// Delete student
app.delete('/api/students/:id', (req, res) => {
    const query = 'DELETE FROM students WHERE id = ?';
    connection.query(query, [req.params.id], (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        if (results.affectedRows === 0) return res.status(404).json({ error: 'Student not found' });
        res.json({ message: 'Student deleted' });
    });
});

// Serve HTML page
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Start server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});

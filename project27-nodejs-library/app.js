const express    = require('express');
const bodyParser = require('body-parser');
const cors       = require('cors');
const mysql      = require('mysql');
const path       = require('path');

const app  = express();
const PORT = 4000;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors());
app.use(express.static(path.join(__dirname, 'public')));

// MySQL connection
const db = mysql.createConnection({
    host:     'localhost',
    user:     'root',
    password: ''
});

db.connect((err) => {
    if (err) { console.log('DB connection error:', err.message); return; }
    console.log('Connected to MySQL');

    // Create database and table
    db.query('CREATE DATABASE IF NOT EXISTS library_db', (err) => {
        if (err) { console.log('DB create error:', err.message); return; }
        db.changeUser({ database: 'library_db' }, (err) => {
            if (err) { console.log('DB switch error:', err.message); return; }

            const createTable = `
                CREATE TABLE IF NOT EXISTS books (
                    book_id    INT AUTO_INCREMENT PRIMARY KEY,
                    title      VARCHAR(200) NOT NULL,
                    author     VARCHAR(100) NOT NULL,
                    year       INT NOT NULL,
                    genre      VARCHAR(100),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            `;
            db.query(createTable, (err) => {
                if (err) console.log('Table error:', err.message);
                else {
                    console.log('Books table ready');
                    // Insert sample books if empty
                    db.query('SELECT COUNT(*) as cnt FROM books', (err, res) => {
                        if (!err && res[0].cnt === 0) {
                            const samples = [
                                ['The Great Gatsby',       'F. Scott Fitzgerald', 1925, 'Fiction'],
                                ['To Kill a Mockingbird',  'Harper Lee',          1960, 'Fiction'],
                                ['1984',                   'George Orwell',       1949, 'Dystopian'],
                                ['Clean Code',             'Robert C. Martin',    2008, 'Technology'],
                                ['The Alchemist',          'Paulo Coelho',        1988, 'Fiction']
                            ];
                            samples.forEach(b => {
                                db.query('INSERT INTO books (title, author, year, genre) VALUES (?,?,?,?)', b);
                            });
                            console.log('Sample books inserted');
                        }
                    });
                }
            });
        });
    });
});

// GET all books
app.get('/api/books', (req, res) => {
    db.query('SELECT * FROM books ORDER BY created_at DESC', (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
});

// GET book by ID
app.get('/api/books/:id', (req, res) => {
    db.query('SELECT * FROM books WHERE book_id = ?', [req.params.id], (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        if (results.length === 0) return res.status(404).json({ error: 'Book not found' });
        res.json(results[0]);
    });
});

// POST add book
app.post('/api/books', (req, res) => {
    const { title, author, year, genre } = req.body;
    if (!title || !author || !year) {
        return res.status(400).json({ error: 'Title, author and year are required' });
    }
    db.query('INSERT INTO books (title, author, year, genre) VALUES (?,?,?,?)',
        [title, author, year, genre || ''],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.status(201).json({ book_id: result.insertId, title, author, year, genre });
        }
    );
});

// DELETE book
app.delete('/api/books/:id', (req, res) => {
    db.query('DELETE FROM books WHERE book_id = ?', [req.params.id], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        if (result.affectedRows === 0) return res.status(404).json({ error: 'Book not found' });
        res.json({ message: 'Book deleted' });
    });
});

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`Library server running on http://localhost:${PORT}`);
});

const express = require('express');
const mysql   = require('mysql');
const path    = require('path');
const app     = express();
const PORT    = 5000;

app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// ── MySQL Connection ──
const db = mysql.createConnection({
    host:     'localhost',
    user:     'root',
    password: ''
});

db.connect((err) => {
    if (err) { console.log('DB connection error:', err.message); return; }
    console.log('Connected to MySQL');

    // Create database
    db.query('CREATE DATABASE IF NOT EXISTS blog_db', (err) => {
        if (err) { console.log('DB create error:', err.message); return; }

        db.changeUser({ database: 'blog_db' }, (err) => {
            if (err) { console.log('DB switch error:', err.message); return; }

            // Create blogs table
            db.query(`
                CREATE TABLE IF NOT EXISTS blogs (
                    id         INT AUTO_INCREMENT PRIMARY KEY,
                    title      VARCHAR(200) NOT NULL,
                    author     VARCHAR(100) NOT NULL,
                    content    TEXT NOT NULL,
                    category   VARCHAR(100) DEFAULT 'General',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            `, (err) => {
                if (err) { console.log('Table error:', err.message); return; }
                console.log('Blogs table ready');

                // Insert sample data if empty
                db.query('SELECT COUNT(*) as cnt FROM blogs', (err, res) => {
                    if (!err && res[0].cnt === 0) {
                        const samples = [
                            ['Getting Started with Node.js', 'Alice', 'Node.js is a JavaScript runtime built on Chrome V8 engine.', 'Technology'],
                            ['Introduction to REST APIs',    'Bob',   'REST stands for Representational State Transfer.',            'Web Development'],
                            ['JavaScript Tips and Tricks',   'Alice', 'Here are some useful JavaScript tips for developers.',        'JavaScript'],
                        ];
                        samples.forEach(s => {
                            db.query('INSERT INTO blogs (title, author, content, category) VALUES (?,?,?,?)', s);
                        });
                        console.log('Sample blogs inserted');
                    }
                });
            });
        });
    });
});

// ── ROUTES ──

// GET all blogs
app.get('/api/blogs', (req, res) => {
    db.query('SELECT * FROM blogs ORDER BY created_at DESC', (err, results) => {
        if (err) return res.status(500).json({ success: false, message: err.message });
        res.json({ success: true, count: results.length, data: results });
    });
});

// GET single blog by ID
app.get('/api/blogs/:id', (req, res) => {
    db.query('SELECT * FROM blogs WHERE id = ?', [req.params.id], (err, results) => {
        if (err) return res.status(500).json({ success: false, message: err.message });
        if (results.length === 0) return res.status(404).json({ success: false, message: 'Blog not found' });
        res.json({ success: true, data: results[0] });
    });
});

// POST create new blog
app.post('/api/blogs', (req, res) => {
    const { title, author, content, category } = req.body;

    if (!title || !author || !content) {
        return res.status(400).json({ success: false, message: 'Title, author and content are required' });
    }

    db.query(
        'INSERT INTO blogs (title, author, content, category) VALUES (?, ?, ?, ?)',
        [title, author, content, category || 'General'],
        (err, result) => {
            if (err) return res.status(500).json({ success: false, message: err.message });

            db.query('SELECT * FROM blogs WHERE id = ?', [result.insertId], (err, rows) => {
                if (err) return res.status(500).json({ success: false, message: err.message });
                res.status(201).json({ success: true, message: 'Blog created successfully', data: rows[0] });
            });
        }
    );
});

// PUT update blog by ID
app.put('/api/blogs/:id', (req, res) => {
    const { title, author, content, category } = req.body;
    const id = req.params.id;

    // First check if exists
    db.query('SELECT * FROM blogs WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json({ success: false, message: err.message });
        if (results.length === 0) return res.status(404).json({ success: false, message: 'Blog not found' });

        const existing = results[0];
        db.query(
            'UPDATE blogs SET title=?, author=?, content=?, category=? WHERE id=?',
            [
                title    || existing.title,
                author   || existing.author,
                content  || existing.content,
                category || existing.category,
                id
            ],
            (err) => {
                if (err) return res.status(500).json({ success: false, message: err.message });

                db.query('SELECT * FROM blogs WHERE id = ?', [id], (err, rows) => {
                    if (err) return res.status(500).json({ success: false, message: err.message });
                    res.json({ success: true, message: 'Blog updated successfully', data: rows[0] });
                });
            }
        );
    });
});

// DELETE blog by ID
app.delete('/api/blogs/:id', (req, res) => {
    const id = req.params.id;

    db.query('SELECT * FROM blogs WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json({ success: false, message: err.message });
        if (results.length === 0) return res.status(404).json({ success: false, message: 'Blog not found' });

        const deleted = results[0];
        db.query('DELETE FROM blogs WHERE id = ?', [id], (err) => {
            if (err) return res.status(500).json({ success: false, message: err.message });
            res.json({ success: true, message: 'Blog deleted successfully', data: deleted });
        });
    });
});

// Root
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`Blog API server running on http://localhost:${PORT}`);
});

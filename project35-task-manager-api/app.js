const express = require('express');
const path    = require('path');
const app     = express();
const PORT    = 5001;

app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// ── In-memory storage ──
let tasks  = [];
let nextId = 1;

// Seed sample tasks
tasks = [
    { id: 1, title: 'Buy groceries',       description: 'Milk, eggs, bread',        status: 'pending',   priority: 'medium', createdAt: new Date().toISOString() },
    { id: 2, title: 'Complete assignment',  description: 'Submit before deadline',   status: 'pending',   priority: 'high',   createdAt: new Date().toISOString() },
    { id: 3, title: 'Morning exercise',     description: '30 minutes jogging',       status: 'completed', priority: 'low',    createdAt: new Date().toISOString() },
    { id: 4, title: 'Read a book',          description: 'Read 20 pages of novel',   status: 'pending',   priority: 'low',    createdAt: new Date().toISOString() },
];
nextId = 5;

// ── ROUTES ──

// GET all tasks
// GET /api/tasks
// Optional query: ?status=pending or ?status=completed
app.get('/api/tasks', (req, res) => {
    let result = tasks;

    if (req.query.status) {
        result = tasks.filter(t => t.status === req.query.status);
    }

    const pending   = tasks.filter(t => t.status === 'pending').length;
    const completed = tasks.filter(t => t.status === 'completed').length;

    res.json({
        success:   true,
        total:     tasks.length,
        pending,
        completed,
        count:     result.length,
        data:      result
    });
});

// GET single task by ID
// GET /api/tasks/:id
app.get('/api/tasks/:id', (req, res) => {
    const task = tasks.find(t => t.id === parseInt(req.params.id));
    if (!task) {
        return res.status(404).json({ success: false, message: 'Task not found' });
    }
    res.json({ success: true, data: task });
});

// POST create new task
// POST /api/tasks
// Body: { title, description, priority }
app.post('/api/tasks', (req, res) => {
    const { title, description, priority } = req.body;

    if (!title) {
        return res.status(400).json({ success: false, message: 'Title is required' });
    }

    const validPriorities = ['low', 'medium', 'high'];
    const taskPriority = validPriorities.includes(priority) ? priority : 'medium';

    const newTask = {
        id:          nextId++,
        title,
        description: description || '',
        status:      'pending',
        priority:    taskPriority,
        createdAt:   new Date().toISOString()
    };

    tasks.push(newTask);
    res.status(201).json({ success: true, message: 'Task created successfully', data: newTask });
});

// PATCH update task status
// PATCH /api/tasks/:id/status
// Body: { status: "completed" or "pending" }
app.patch('/api/tasks/:id/status', (req, res) => {
    const index = tasks.findIndex(t => t.id === parseInt(req.params.id));
    if (index === -1) {
        return res.status(404).json({ success: false, message: 'Task not found' });
    }

    const { status } = req.body;

    if (!status) {
        return res.status(400).json({ success: false, message: 'Status field is required. Make sure Content-Type is application/json in Postman.' });
    }

    if (!['pending', 'completed'].includes(status)) {
        return res.status(400).json({ success: false, message: 'Status must be pending or completed' });
    }

    tasks[index].status = status;
    res.json({ success: true, message: `Task marked as ${status}`, data: tasks[index] });
});

// PUT update full task
// PUT /api/tasks/:id
// Body: { title, description, priority, status }
app.put('/api/tasks/:id', (req, res) => {
    const index = tasks.findIndex(t => t.id === parseInt(req.params.id));
    if (index === -1) {
        return res.status(404).json({ success: false, message: 'Task not found' });
    }

    const { title, description, priority, status } = req.body;
    tasks[index] = {
        ...tasks[index],
        title:       title       || tasks[index].title,
        description: description !== undefined ? description : tasks[index].description,
        priority:    priority    || tasks[index].priority,
        status:      status      || tasks[index].status,
    };

    res.json({ success: true, message: 'Task updated successfully', data: tasks[index] });
});

// DELETE task by ID
// DELETE /api/tasks/:id
app.delete('/api/tasks/:id', (req, res) => {
    const index = tasks.findIndex(t => t.id === parseInt(req.params.id));
    if (index === -1) {
        return res.status(404).json({ success: false, message: 'Task not found' });
    }

    const deleted = tasks.splice(index, 1)[0];
    res.json({ success: true, message: 'Task deleted successfully', data: deleted });
});

// Root
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`Task Manager API running on http://localhost:${PORT}`);
    console.log('Test with Postman or browser');
});

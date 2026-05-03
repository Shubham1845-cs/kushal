import { useState, useEffect } from 'react'
import { store, addNotification, removeNotification, clearAll } from './store'

let nextId = 1

const typeColors = {
  success: { bg: '#e8f5e9', border: '#4caf50', label: '#2e7d32' },
  error:   { bg: '#ffebee', border: '#d32f2f', label: '#c62828' },
  warning: { bg: '#fff8e1', border: '#ff9800', label: '#e65100' },
  info:    { bg: '#e3f2fd', border: '#0066cc', label: '#0066cc' },
}

function App() {
  const [notifications, setNotifications] = useState(store.getState())
  const [message, setMessage] = useState('')
  const [type,    setType]    = useState('info')

  useEffect(() => {
    const unsub = store.subscribe(() => setNotifications([...store.getState()]))
    return unsub
  }, [])

  function handleAdd() {
    if (!message.trim()) return
    store.dispatch(addNotification({
      id:      nextId++,
      message: message.trim(),
      type,
      time:    new Date().toLocaleTimeString()
    }))
    setMessage('')
  }

  const s = {
    page: { background: '#f0f0f0', minHeight: '100vh', padding: '20px', fontFamily: 'Arial, sans-serif' },
    card: { background: 'white', padding: '25px', borderRadius: '5px', boxShadow: '0 0 8px rgba(0,0,0,0.1)', marginBottom: '20px' },
    input: { width: '100%', padding: '9px', border: '1px solid #ddd', borderRadius: '3px', fontSize: '14px', marginBottom: '10px' },
    select: { width: '100%', padding: '9px', border: '1px solid #ddd', borderRadius: '3px', fontSize: '14px', marginBottom: '10px' },
    btnAdd:   { padding: '9px 20px', border: 'none', borderRadius: '3px', cursor: 'pointer', fontWeight: 'bold', background: '#0066cc', color: 'white', marginRight: '10px' },
    btnClear: { padding: '9px 20px', border: 'none', borderRadius: '3px', cursor: 'pointer', fontWeight: 'bold', background: '#888', color: 'white' },
  }

  return (
    <div style={s.page}>
      <div style={{ maxWidth: '700px', margin: '0 auto' }}>
        <h1 style={{ textAlign: 'center', color: '#333', marginBottom: '20px' }}>Notification System (Redux)</h1>

        <div style={s.card}>
          <h2 style={{ marginBottom: '15px', borderBottom: '2px solid #0066cc', paddingBottom: '8px' }}>Add Notification</h2>
          <label style={{ display: 'block', fontWeight: 'bold', marginBottom: '4px' }}>Message</label>
          <input style={s.input} type="text" value={message}
            onChange={e => setMessage(e.target.value)}
            onKeyDown={e => e.key === 'Enter' && handleAdd()}
            placeholder="Enter notification message" />
          <label style={{ display: 'block', fontWeight: 'bold', marginBottom: '4px' }}>Type</label>
          <select style={s.select} value={type} onChange={e => setType(e.target.value)}>
            <option value="info">Info</option>
            <option value="success">Success</option>
            <option value="warning">Warning</option>
            <option value="error">Error</option>
          </select>
          <button style={s.btnAdd} onClick={handleAdd}>Add Notification</button>
        </div>

        <div style={s.card}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '15px', borderBottom: '2px solid #0066cc', paddingBottom: '8px' }}>
            <h2 style={{ margin: 0 }}>
              Notifications
              {notifications.length > 0 && (
                <span style={{ background: '#0066cc', color: 'white', borderRadius: '10px', padding: '1px 8px', fontSize: '12px', marginLeft: '8px' }}>
                  {notifications.length}
                </span>
              )}
            </h2>
            {notifications.length > 0 && (
              <button style={s.btnClear} onClick={() => store.dispatch(clearAll())}>Clear All</button>
            )}
          </div>

          {notifications.length === 0
            ? <p style={{ textAlign: 'center', padding: '30px', color: '#888' }}>No notifications yet. Add one above.</p>
            : notifications.map(n => {
                const c = typeColors[n.type]
                return (
                  <div key={n.id} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', padding: '12px 15px', borderRadius: '4px', borderLeft: '4px solid ' + c.border, background: c.bg, marginBottom: '10px' }}>
                    <div>
                      <p style={{ fontWeight: 'bold', fontSize: '14px', color: '#333' }}>{n.message}</p>
                      <p style={{ fontSize: '12px', color: c.label, marginTop: '2px' }}>{n.type.toUpperCase()}</p>
                      <p style={{ fontSize: '11px', color: '#999', marginTop: '2px' }}>{n.time}</p>
                    </div>
                    <button onClick={() => store.dispatch(removeNotification(n.id))}
                      style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: '18px', color: '#888', padding: '0 4px' }}>
                      ×
                    </button>
                  </div>
                )
              })
          }
        </div>
      </div>
    </div>
  )
}

export default App

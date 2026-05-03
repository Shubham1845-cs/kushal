import { useState } from 'react'

function App() {
  const [isDark, setIsDark] = useState(false)

  const theme = {
    background: isDark ? '#1a1a1a' : '#ffffff',
    color:      isDark ? '#f0f0f0' : '#333333',
    cardBg:     isDark ? '#2d2d2d' : '#f5f5f5',
    border:     isDark ? '#444'    : '#ddd',
    btnBg:      isDark ? '#f0f0f0' : '#333333',
    btnColor:   isDark ? '#333333' : '#ffffff',
  }

  return (
    <div style={{ minHeight: '100vh', background: theme.background, color: theme.color, padding: '40px 20px', fontFamily: 'Arial, sans-serif', transition: 'background 0.3s, color 0.3s' }}>
      <div style={{ maxWidth: '500px', margin: '0 auto', background: theme.cardBg, border: '1px solid ' + theme.border, borderRadius: '5px', padding: '30px', textAlign: 'center' }}>

        <h1 style={{ marginBottom: '10px' }}>Theme Switcher</h1>
        <p style={{ fontSize: '14px', color: isDark ? '#aaa' : '#666', marginBottom: '20px' }}>
          Toggle between Light and Dark mode using React useState Hook
        </p>

        <div style={{ padding: '15px', border: '1px solid ' + theme.border, borderRadius: '3px', marginBottom: '15px' }}>
          <p style={{ fontSize: '18px', fontWeight: 'bold' }}>
            Current Mode: <span style={{ color: isDark ? '#90caf9' : '#e65100' }}>
              {isDark ? 'Dark Mode' : 'Light Mode'}
            </span>
          </p>
        </div>

        <div style={{ padding: '15px', border: '1px solid ' + theme.border, borderRadius: '3px', marginBottom: '15px', textAlign: 'left' }}>
          <p style={{ marginBottom: '8px' }}>Sample Form Elements:</p>
          <input type="text" placeholder="Sample input field"
            style={{ width: '100%', padding: '8px', background: theme.background, color: theme.color, border: '1px solid ' + theme.border, borderRadius: '3px', marginBottom: '8px' }} />
          <select style={{ width: '100%', padding: '8px', background: theme.background, color: theme.color, border: '1px solid ' + theme.border, borderRadius: '3px' }}>
            <option>Option 1</option>
            <option>Option 2</option>
          </select>
        </div>

        <p style={{ fontSize: '13px', color: isDark ? '#aaa' : '#666', marginBottom: '5px' }}>
          Background: {theme.background} | Text: {theme.color}
        </p>

        <button onClick={() => setIsDark(!isDark)}
          style={{ padding: '10px 25px', background: theme.btnBg, color: theme.btnColor, border: 'none', borderRadius: '3px', cursor: 'pointer', fontWeight: 'bold', fontSize: '15px', marginTop: '20px' }}>
          Switch to {isDark ? 'Light' : 'Dark'} Mode
        </button>
      </div>
    </div>
  )
}

export default App

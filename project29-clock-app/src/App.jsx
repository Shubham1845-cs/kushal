import { useState, useEffect } from 'react'

function App() {
  const [time, setTime]       = useState(new Date())
  const [running, setRunning] = useState(true)

  useEffect(() => {
    if (!running) return
    const timer = setInterval(() => setTime(new Date()), 1000)
    return () => clearInterval(timer)
  }, [running])

  const pad = (n) => String(n).padStart(2, '0')
  const hh  = pad(time.getHours())
  const mm  = pad(time.getMinutes())
  const ss  = pad(time.getSeconds())

  const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
  const dateStr = `${days[time.getDay()]}, ${time.getDate()} ${months[time.getMonth()]} ${time.getFullYear()}`

  const pageStyle = {
    minHeight: '100vh', background: '#111',
    display: 'flex', alignItems: 'center', justifyContent: 'center'
  }

  const cardStyle = {
    background: '#222', padding: '40px 30px',
    borderRadius: '8px', textAlign: 'center'
  }

  const clockStyle = {
    fontSize: '64px', fontWeight: 'bold', letterSpacing: '4px',
    color: running ? '#00e676' : '#ff5252',
    fontFamily: '"Courier New", monospace'
  }

  const btnStyle = {
    marginTop: '20px', padding: '10px 30px', border: 'none',
    borderRadius: '3px', cursor: 'pointer', fontWeight: 'bold',
    fontSize: '15px',
    background: running ? '#ff5252' : '#00e676',
    color: '#222'
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <h2 style={{ color: '#aaa', marginBottom: '20px', letterSpacing: '2px', fontSize: '16px' }}>
          DIGITAL CLOCK
        </h2>
        <div style={clockStyle}>
          {hh}:{mm}:{ss}
        </div>
        <p style={{ color: '#888', marginTop: '12px', fontSize: '14px' }}>{dateStr}</p>
        <p style={{ color: running ? '#00e676' : '#ff5252', marginTop: '8px', fontSize: '13px', fontWeight: 'bold' }}>
          {running ? 'Running' : 'Stopped'}
        </p>
        <button style={btnStyle} onClick={() => setRunning(!running)}>
          {running ? 'Stop Clock' : 'Start Clock'}
        </button>
      </div>
    </div>
  )
}

export default App

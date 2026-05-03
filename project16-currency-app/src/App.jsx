import { useState } from 'react'

const RATE = 83.5 // 1 USD = 83.5 INR

function App() {
  const [dollars, setDollars] = useState('')
  const [rupees,  setRupees]  = useState(null)
  const [error,   setError]   = useState('')

  function handleConvert() {
    if (dollars === '' || isNaN(dollars)) {
      setError('Please enter a valid number.')
      return
    }
    if (parseFloat(dollars) < 0) {
      setError('Amount cannot be negative.')
      return
    }
    setError('')
    setRupees((parseFloat(dollars) * RATE).toFixed(2))
  }

  function handleReset() {
    setDollars('')
    setRupees(null)
    setError('')
  }

  const containerStyle = {
    maxWidth: '500px', margin: '60px auto', background: 'white',
    padding: '30px', borderRadius: '5px', boxShadow: '0 0 10px rgba(0,0,0,0.1)'
  }

  const inputStyle = {
    width: '100%', padding: '10px', border: '1px solid #ddd',
    borderRadius: '3px', fontSize: '16px', marginBottom: '15px'
  }

  const btnStyle = {
    width: '100%', padding: '12px', border: 'none',
    borderRadius: '3px', fontSize: '16px', cursor: 'pointer',
    fontWeight: 'bold', color: 'white'
  }

  return (
    <div style={{ background: '#f0f0f0', minHeight: '100vh', padding: '20px', fontFamily: 'Arial, sans-serif' }}>
      <div style={containerStyle}>
        <h1 style={{ textAlign: 'center', color: '#333', marginBottom: '25px', borderBottom: '2px solid #0066cc', paddingBottom: '10px' }}>
          Currency Converter
        </h1>

        <label style={{ display: 'block', fontWeight: 'bold', color: '#333', marginBottom: '5px' }}>
          Amount in US Dollars ($):
        </label>
        <input
          type="number" placeholder="Enter amount in USD"
          value={dollars} onChange={e => { setDollars(e.target.value); setRupees(null); setError('') }}
          min="0" style={inputStyle}
        />

        {error && <p style={{ color: 'red', fontSize: '13px', marginBottom: '10px' }}>{error}</p>}

        <button onClick={handleConvert} style={{ ...btnStyle, background: '#0066cc', marginBottom: '10px' }}>
          Convert to Indian Rupees
        </button>
        <button onClick={handleReset} style={{ ...btnStyle, background: '#888' }}>
          Reset
        </button>

        {rupees !== null && (
          <div style={{ marginTop: '20px', background: '#e8f4ff', padding: '15px', borderRadius: '3px', borderLeft: '4px solid #0066cc' }}>
            <p style={{ fontSize: '18px', color: '#333' }}>
              ${dollars} USD = <span style={{ fontWeight: 'bold', color: '#0066cc', fontSize: '22px' }}>₹{rupees} INR</span>
            </p>
          </div>
        )}

        <p style={{ marginTop: '10px', fontSize: '13px', color: '#666', textAlign: 'center' }}>
          Exchange Rate: 1 USD = ₹{RATE} INR (fixed)
        </p>
      </div>
    </div>
  )
}

export default App

import { useState, useEffect } from 'react'
import { store, setCategory, setMaxPrice, resetFilters } from './store'

const PRODUCTS = [
  { id:1,  name:'Laptop Pro',     category:'Electronics', price:75000 },
  { id:2,  name:'Wireless Mouse', category:'Electronics', price:1200  },
  { id:3,  name:'Python Book',    category:'Books',       price:450   },
  { id:4,  name:'React Handbook', category:'Books',       price:600   },
  { id:5,  name:'Running Shoes',  category:'Sports',      price:3500  },
  { id:6,  name:'Yoga Mat',       category:'Sports',      price:800   },
  { id:7,  name:'Coffee Mug',     category:'Kitchen',     price:350   },
  { id:8,  name:'Headphones',     category:'Electronics', price:5000  },
  { id:9,  name:'Notebook Set',   category:'Books',       price:250   },
  { id:10, name:'Water Bottle',   category:'Sports',      price:600   },
  { id:11, name:'Blender',        category:'Kitchen',     price:2500  },
  { id:12, name:'Keyboard',       category:'Electronics', price:2200  },
]

const categories = ['All', ...new Set(PRODUCTS.map(p => p.category))]

function App() {
  const [filters, setFilters] = useState(store.getState())

  useEffect(() => {
    const unsub = store.subscribe(() => setFilters({ ...store.getState() }))
    return unsub
  }, [])

  const filtered = PRODUCTS.filter(p =>
    (filters.category === 'All' || p.category === filters.category) &&
    p.price <= filters.maxPrice
  )

  const s = {
    page:    { background: '#f0f0f0', minHeight: '100vh', padding: '20px', fontFamily: 'Arial, sans-serif' },
    card:    { background: 'white', padding: '20px', borderRadius: '5px', boxShadow: '0 0 8px rgba(0,0,0,0.1)', marginBottom: '20px' },
    grid:    { display: 'flex', flexWrap: 'wrap', gap: '15px' },
    product: { background: 'white', padding: '15px', borderRadius: '5px', boxShadow: '0 0 8px rgba(0,0,0,0.1)', width: 'calc(33% - 10px)', minWidth: '200px' },
    badge:   { display: 'inline-block', padding: '2px 8px', borderRadius: '3px', fontSize: '11px', fontWeight: 'bold', background: '#e3f2fd', color: '#1565c0', marginBottom: '8px' },
    btn:     { padding: '8px 18px', border: 'none', borderRadius: '3px', cursor: 'pointer', fontWeight: 'bold', background: '#888', color: 'white' },
  }

  return (
    <div style={s.page}>
      <div style={{ maxWidth: '900px', margin: '0 auto' }}>
        <h1 style={{ textAlign: 'center', color: '#333', marginBottom: '20px' }}>Product Filter (Redux)</h1>

        <div style={s.card}>
          <h2 style={{ marginBottom: '12px', fontSize: '16px' }}>Filters</h2>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: '15px', alignItems: 'center' }}>
            <div>
              <label style={{ fontWeight: 'bold', marginRight: '8px' }}>Category:</label>
              <select value={filters.category}
                onChange={e => store.dispatch(setCategory(e.target.value))}
                style={{ padding: '6px 10px', border: '1px solid #ddd', borderRadius: '3px' }}>
                {categories.map(c => <option key={c}>{c}</option>)}
              </select>
            </div>
            <div>
              <label style={{ fontWeight: 'bold', marginRight: '8px' }}>
                Max Price: ₹{filters.maxPrice.toLocaleString()}
              </label>
              <input type="range" min="250" max="100000" step="250"
                value={filters.maxPrice}
                onChange={e => store.dispatch(setMaxPrice(parseInt(e.target.value)))}
                style={{ width: '200px' }} />
            </div>
            <button style={s.btn} onClick={() => store.dispatch(resetFilters())}>Reset Filters</button>
          </div>
        </div>

        <p style={{ color: '#666', fontSize: '13px', marginBottom: '15px' }}>
          Showing {filtered.length} of {PRODUCTS.length} products
        </p>

        <div style={s.grid}>
          {filtered.length === 0
            ? <p style={{ textAlign: 'center', padding: '40px', color: '#888', width: '100%' }}>No products match the selected filters.</p>
            : filtered.map(p => (
              <div style={s.product} key={p.id}>
                <span style={s.badge}>{p.category}</span>
                <h3 style={{ color: '#333', marginBottom: '6px', fontSize: '15px' }}>{p.name}</h3>
                <p style={{ fontWeight: 'bold', color: '#0066cc', fontSize: '16px' }}>₹{p.price.toLocaleString()}</p>
              </div>
            ))
          }
        </div>
      </div>
    </div>
  )
}

export default App

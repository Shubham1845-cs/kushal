import { createStore } from 'redux'

const SET_CATEGORY  = 'SET_CATEGORY'
const SET_MAX_PRICE = 'SET_MAX_PRICE'
const RESET_FILTERS = 'RESET_FILTERS'

const initialState = { category: 'All', maxPrice: 100000 }

function filterReducer(state = initialState, action) {
  switch (action.type) {
    case SET_CATEGORY:  return { ...state, category: action.payload }
    case SET_MAX_PRICE: return { ...state, maxPrice: action.payload }
    case RESET_FILTERS: return { ...initialState }
    default:            return state
  }
}

export const store = createStore(filterReducer)
export const setCategory  = (val) => ({ type: SET_CATEGORY,  payload: val })
export const setMaxPrice  = (val) => ({ type: SET_MAX_PRICE, payload: val })
export const resetFilters = ()    => ({ type: RESET_FILTERS })

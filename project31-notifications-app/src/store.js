import { createStore } from 'redux'

const ADD_NOTIFICATION    = 'ADD_NOTIFICATION'
const REMOVE_NOTIFICATION = 'REMOVE_NOTIFICATION'
const CLEAR_ALL           = 'CLEAR_ALL'

function notifReducer(state = [], action) {
  switch (action.type) {
    case ADD_NOTIFICATION:    return [action.payload, ...state]
    case REMOVE_NOTIFICATION: return state.filter(n => n.id !== action.payload)
    case CLEAR_ALL:           return []
    default:                  return state
  }
}

export const store = createStore(notifReducer)
export const addNotification    = (notif) => ({ type: ADD_NOTIFICATION,    payload: notif })
export const removeNotification = (id)    => ({ type: REMOVE_NOTIFICATION, payload: id })
export const clearAll           = ()      => ({ type: CLEAR_ALL })

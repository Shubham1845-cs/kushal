# Project 31 - React Notifications with Redux (Vite)

## Description
A React application that displays system notifications using Redux to manage notification state.

## Tech Stack
- React 18, Vite
- Redux (createStore)
- useState, useEffect Hooks

## Prerequisites
- Node.js installed

## Setup & Run
```bash
npm install
npm run dev
```

## URL
http://localhost:5177 (or next available port)

## Features
- Add notifications with custom message and type
- Notification types: Info, Success, Warning, Error
- Each notification shows message, type, and timestamp
- Dismiss individual notifications (x button)
- Clear All button to remove all notifications
- Notification count badge
- Redux store manages notification state
- Actions: ADD_NOTIFICATION, REMOVE_NOTIFICATION, CLEAR_ALL
- Reducer handles all notification state updates

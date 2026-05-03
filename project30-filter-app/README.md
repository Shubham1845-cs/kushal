# Project 30 - React Product Filter with Redux (Vite)

## Description
A React application that filters products by category and price range using Redux to manage filter state.

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
http://localhost:5176 (or next available port)

## Features
- 12 sample products across 4 categories
- Filter by category (All, Electronics, Books, Sports, Kitchen)
- Filter by max price using range slider
- Reset filters button
- Shows filtered count vs total count
- Redux store manages filter state
- Actions: SET_CATEGORY, SET_MAX_PRICE, RESET_FILTERS
- Reducer handles all filter logic

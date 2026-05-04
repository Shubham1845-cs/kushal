# Project 9 - Spring Boot Product Inventory Management System

## Question
Develop a Spring Boot-based Product Inventory Management System that stores and manages product details using MongoDB as the database.

## Tech Stack
- Java 17, Spring Boot 3.0
- Spring Data MongoDB
- Spring Security (Basic Authentication)
- Maven

## Prerequisites
- Java JDK 17+ installed
- Maven installed
- MongoDB running on localhost:27017

## Start MongoDB
```powershell
net start MongoDB
```

## Setup & Run
```bash
cd project9-spring-inventory
mvn spring-boot:run
```

## URL
http://localhost:9090

## Authentication (Basic Auth)
- Username: `admin`
- Password: `admin123`

In Postman: Authorization tab → Basic Auth → enter credentials above

## Database
- MongoDB database: `inventory_db`
- Collection: `products`
- View in MongoDB Compass: mongodb://localhost:27017

## REST API Endpoints

| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/products | Get all products |
| GET | /api/products/{id} | Get product by ID |
| GET | /api/products/category/{category} | Get by category |
| GET | /api/products/search/{name} | Search by name |
| POST | /api/products | Create product |
| PUT | /api/products/{id} | Update product |
| DELETE | /api/products/{id} | Delete product |

## POST/PUT Body (JSON)
```json
{
  "name": "Keyboard",
  "category": "Electronics",
  "price": 2500.00,
  "quantity": 30,
  "description": "Mechanical keyboard"
}
```

## Notes
- 5 sample products are auto-loaded on first run
- All endpoints require Basic Auth
- Set Body → raw → JSON in Postman for POST/PUT

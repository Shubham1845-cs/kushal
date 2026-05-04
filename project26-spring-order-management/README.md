# Project 26 - Spring Boot Order Management System

## Question
Develop a Spring Boot-based Order Management System that provides REST APIs for managing customer orders. The application should allow users to create, view, update, and delete orders.

## Tech Stack
- Java 17, Spring Boot 3.0
- Spring Data JPA + MySQL (XAMPP)
- Maven

## Prerequisites
- Java JDK 17+ installed
- Maven installed
- XAMPP MySQL running

## Setup & Run

### Step 1 - Start XAMPP MySQL

### Step 2 - Run the application
```bash
cd project26-spring-order-management
mvn clean spring-boot:run
```
Database `order_db` and `orders` table are auto-created on first run.

## URL
http://localhost:8085

## Database
- Database: `order_db` (auto-created)
- Table: `orders`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Order Status Values
`PENDING` | `CONFIRMED` | `SHIPPED` | `DELIVERED` | `CANCELLED`

## REST API Endpoints

| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/orders | Get all orders |
| GET | /api/orders/{id} | Get order by ID |
| GET | /api/orders/status/{status} | Get by status |
| GET | /api/orders/customer/{email} | Get by customer email |
| POST | /api/orders | Create order |
| PUT | /api/orders/{id} | Update order |
| DELETE | /api/orders/{id} | Delete order |

## POST/PUT Body (JSON)
```json
{
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 1,
  "totalPrice": 75000.00,
  "shippingAddress": "123 Main St, Pune",
  "status": "PENDING"
}
```

## Postman Testing
- Set Body → raw → JSON for POST/PUT requests
- No authentication required

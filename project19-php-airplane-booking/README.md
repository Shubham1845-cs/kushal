# Project 19 - PHP Airplane Seat Booking

## Description
An airplane seat booking system with a visual seat map. Users can select seats, book them with passenger details, and cancel bookings.

## Tech Stack
- PHP, MySQL
- XAMPP Apache + MySQL

## Prerequisites
- XAMPP Apache running on port 8081
- XAMPP MySQL running

## Setup
Visit init_db.php first (creates 72 seats):
http://localhost:8081/project19-php-airplane-booking/init_db.php

## URL
http://localhost:8081/project19-php-airplane-booking/index.php

## Database
- Database: `airplane_db`
- Table: `seats`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Visual seat map (rows 1-13, seats A-F)
- Business class: rows 1-3 (blue)
- Economy class: rows 4-13 (green)
- Booked seats shown in red
- Click seat to select, fill form to book
- Cancel booking from booked seats list
- Total/Booked/Available stats

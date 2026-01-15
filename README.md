# HRIS (Human Resource Information System)

## Overview
HRIS is a Human Resource Information System built using Laravel 12 to help organizations manage employee data, tasks, attendance, payroll, and leave requests in a single integrated platform.

The application supports two types of users: **Admin (HR)** and **Employee**, each with different access levels and features. This project was developed as a Fullstack Developer portfolio, focusing on real-world HR business processes and clean implementation using Laravel best practices.

## Key Features

### Authentication and Authorization
- User authentication (login and logout)
- Role-based access control (Admin and Employee)
- Middleware for access restriction based on user roles

### Employee Management
- Create, read, update, and delete employee data
- Employee detail view
- Relationship between employees, departments, and roles

### Department and Role Management
- Department management (CRUD)
- Role management (CRUD)
- Organizational structure grouping

### Task Management
- Task management by Admin
- Task assignment to employees
- Task status updates (Pending, In Progress, Completed)
- Separate task views for Admin and Employee

### Presence (Attendance)
- Employee attendance records
- Update and delete attendance data
- Attendance recap

### Payroll Management
- Payroll management (CRUD)
- Salary calculation
- Salary slip view
- Payroll access restricted by user role

### Leave Request
- Leave request submission by employees
- Approval or rejection by Admin
- Leave request status tracking

### Dashboard
- Summary of total data
- Latest tasks overview
- Attendance statistics chart

## Tech Stack

### Backend
- Laravel 12
- PHP 8+
- MySQL

### Frontend
- Blade Template Engine
- Tailwind CSS
- Alpine.js

### Additional Tools
- Laravel Breeze (Authentication)
- Flatpickr
- Chart.js

## Database Design
The database structure was designed using an ERD before implementation.  
Main tables include:
- users
- employees
- departments
- roles
- tasks
- presences
- payrolls
- leave_requests

## Installation Guide

1. Clone Repository
bash 
git clone https://github.com/muhamadmuhibudin/hrisapp.git
cd hrisapp

2. Install Dependencies
composer install
npm install
npm run dev

3. Environment Setup
cp .env.example .env
php artisan key:generate
Configure the database credentials in the .env file.

4. Migration and Seeding
php artisan migrate --seed

5. Run Application
php artisan serve


The application will be available at http://localhost:8000 after setup.

## Demo Account
*(To be configured after installation)*

**Admin**  
- Email: admin@example.com  
- Password: password

**Employee**  
- Email: employee@example.com  
- Password: password

## What This Project Demonstrates
- End-to-end HRIS implementation  
- Fullstack development using Laravel  
- Role-based authorization  
- Relational database management  
- Separation of features between Admin and Employee roles

## Future Improvements
- Export payroll as PDF  
- Email notifications  
- REST API implementation  
- Unit and feature testing

## Author
Muhammad Muhibbudin  
Fullstack Web Developer

## Notes for Recruiter
This project simulates a real-world HRIS commonly used in companies. It was developed with a structured approach following Laravel best practices, with a focus on business logic, access control, and maintainable code.
>>>>>>> 14de12303f07172bd06e8d6769659a0764b3a7c9

# HRIS (Human Resource Information System)

> A portfolio project simulating a real-world HRIS used in modern companies.
> 🔗 Live Demo: https://hrisapp.up.railway.app


## Overview
HRIS is a Human Resource Information System built with Laravel 12 to manage employee data, tasks, attendance, payroll, and leave requests in one integrated platform.

The application supports **Super Admin**, **HR**, and **Employee** roles with different access levels and workflows. This project was developed as a Fullstack Developer portfolio, focusing on real-world HR business processes and Laravel best practices.

The application follows production-ready practices including role-based authorization, structured database design, and scalable architecture.

---

## Key Features

- Authentication & role-based authorization (Super Admin, HR, Employee)
- Employee, department, and role management (CRUD)
- Task assignment and status tracking
- Attendance (presence) management and recap
- Payroll management with salary calculation and salary slip
- Leave request workflow with approval and status tracking
- Dashboard with summary data, charts, and recent activities

---

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

---

## Database Design
The database was designed using an ERD before implementation.  
Main tables include:
- users
- employees
- departments
- roles
- tasks
- presences
- payrolls
- leave_requests

---

## Installation Guide


```bash
git clone https://github.com/muhamadmuhibudin/hrisapp.git
cd hrisapp

composer install
npm install
npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```

The application will be available at:  
http://localhost:8000

---

## Demo Accounts

### Super Admin
- Email: `superadmin@example.com`
- Password: `password`

### HR
- Email: `hr@example.com`
- Password: `password`

### Employee
- Email: `employee@example.com`
- Password: `password`

---

## Purpose of This Project

- Demonstrates fullstack web development skills  
- Implements real-world HR business workflows  
- Applies role-based authorization and access control  
- Uses relational database design and deployment-ready architecture  

---

## Remote Readiness

- Clean and maintainable Laravel project structure
- Clear role-based access control and authorization flow
- Database designed with scalability in mind
- Ready for API extension and SPA/mobile integration
- Deployed and tested in a cloud environment

---

## System Design Highlights

- Controlled user registration with Super Admin approval and role assignment

- Clear role-based access control (Super Admin, HR, Employee)

- Business logic structured for maintainability and future scaling

- Designed for auditability, background jobs, and API expansion

---

## Future Improvements

- Audit and activity logging for critical HR actions

- Soft delete with restore and scheduled permanent deletion

- REST API for SPA or mobile integration

- Feature and authorization tests for critical workflows

---

## Author

**Muhammad Muhibbudin**  
Fullstack Web Developer

---

## Notes for Recruiter

This project simulates a real-world HRIS commonly used in companies.  
It was developed with a structured approach following Laravel best practices, with a focus on business logic, access control, and maintainable code.

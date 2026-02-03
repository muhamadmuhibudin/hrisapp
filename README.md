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

## Future Improvements

- Add unit & feature tests to improve code quality and reliability  
- Implement REST API endpoints for SPA or mobile app integration  
- Add email notifications for HR workflows (leave approval, payroll, etc.)  
- Improve UI/UX with better loading and empty states  
- Add activity logs for important HR actions  
- Support data export (CSV/Excel) for reports  
- Implement Super Admin approval for user registration, including role assignment and user activation  
- Strengthen authorization using Laravel Policies and Gates for better access control  

---

## Author

**Muhammad Muhibbudin**  
Fullstack Web Developer

---

## Notes for Recruiter

This project simulates a real-world HRIS commonly used in companies.  
It was developed with a structured approach following Laravel best practices, with a focus on business logic, access control, and maintainable code.

<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
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

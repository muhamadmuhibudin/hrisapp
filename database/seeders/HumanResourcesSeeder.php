<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HumanResourcesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Departments
        DB::table('departments')->insert([
            [
                'name' => 'HR',
                'description' => 'Department Human Resources',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IT',
                'description' => 'Department Information Technology',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales',
                'description' => 'Department Sales',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Roles
        DB::table('roles')->insert([
            [
                'title' => 'HR',
                'description' => 'Handling team',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Developer',
                'description' => 'Handling codes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sales',
                'description' => 'Handling selling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Employees
        DB::table('employees')->insert([
            [
                'fullname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'birth_date' => $faker->date('Y-m-d', '-20 years'),
                'hire_date' => now(),
                'department_id' => 1,
                'role_id' => 1,
                'status' => 'active',
                'salary' => $faker->randomFloat(2, 3000, 8000),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fullname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'birth_date' => $faker->date('Y-m-d', '-20 years'),
                'hire_date' => now(),
                'department_id' => 2,
                'role_id' => 2,
                'status' => 'active',
                'salary' => $faker->randomFloat(2, 3000, 8000),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Tasks
        DB::table('tasks')->insert([
            [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'assigned_to' => 1,
                'due_date' => '2026-12-31',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'assigned_to' => 2,
                'due_date' => '2026-12-31',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Payroll
        DB::table('payroll')->insert([
            [
                'title' => 'Monthly Salary January 2026',
                'employee_id' => 1,
                'salary' => $faker->randomFloat(2, 3000, 8000),
                'bonuses' => $faker->randomFloat(2, 500, 800),
                'deductions' => $faker->randomFloat(2, 500, 800),
                'net_salary' => $faker->randomFloat(2, 3000, 8000),
                'pay_date' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monthly Salary January 2026',
                'employee_id' => 2,
                'salary' => $faker->randomFloat(2, 3000, 8000),
                'bonuses' => $faker->randomFloat(2, 500, 800),
                'deductions' => $faker->randomFloat(2, 500, 800),
                'net_salary' => $faker->randomFloat(2, 3000, 8000),
                'pay_date' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Presences
        DB::table('presences')->insert([
            [
                'employee_id' => 1,
                'check_in' => '2026-01-01 08:00:00',
                'check_out' => '2026-01-01 16:00:00',
                'date' => '2026-01-01',
                'status' => 'present',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employee_id' => 2,
                'check_in' => '2026-01-01 08:00:00',
                'check_out' => '2026-01-01 16:00:00',
                'date' => '2026-01-01',
                'status' => 'present',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Leave Requests
        DB::table('leave_requests')->insert([
            [
                'employee_id' => 1,
                'reason' => 'Sick Leave',
                'start_date' => '2026-01-20',
                'end_date' => '2026-01-23',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employee_id' => 2,
                'reason' => 'Vacation',
                'start_date' => '2026-01-20',
                'end_date' => '2026-01-23',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
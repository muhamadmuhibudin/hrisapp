<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HumanResourcesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Departments
        DB::table('departments')->updateOrInsert(
            ['name' => 'HR'],
            [
                'description' => 'Department Human Resources',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('departments')->updateOrInsert(
            ['name' => 'IT'],
            [
                'description' => 'Department Information Technology',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('departments')->updateOrInsert(
            ['name' => 'Sales'],
            [
                'description' => 'Department Sales',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Roles
        DB::table('roles')->updateOrInsert(
            ['title' => 'Super Admin'],
            [
                'description' => 'Full system access',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->updateOrInsert(
            ['title' => 'HR Manager'],
            [
                'description' => 'Manage employees & payroll',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->updateOrInsert(
            ['title' => 'Employee'],
            [
                'description' => 'Self-service access',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Employees
        DB::table('employees')->updateOrInsert(
            ['email' => 'test@example.com'],
            [
                'fullname' => 'Muna Smith',
                'phone_number' => '081234567890',
                'address' => 'Jl. Mawar No. 1',
                'birth_date' => '1990-01-01',
                'hire_date' => now(),
                'department_id' => 1,
                'role_id' => 2, // HR
                'status' => 'active',
                'salary' => 8000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('employees')->updateOrInsert(
            ['email' => 'employee@example.com'],
            [
                'fullname' => 'Deni Wijaya',
                'phone_number' => '082233445566',
                'address' => 'Jl. Melati No. 2',
                'birth_date' => '1995-02-02',
                'hire_date' => now(),
                'department_id' => 3,
                'role_id' => 3, // Employee
                'status' => 'active',
                'salary' => 4000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('employees')->updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'fullname' => 'Dr. John Doe',
                'phone_number' => '082737384937',
                'address' => 'Wall Street 128',
                'birth_date' => '1985-03-03',
                'hire_date' => now(),
                'department_id' => 2,
                'role_id' => 1, // Super Admin
                'status' => 'active',
                'salary' => 12000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Users
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Dr. John Doe',
                'password' => Hash::make('password'),
                'employee_id' => 3,
                'role_id' => 1, // Super Admin
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'hr@example.com'],
            [
                'name' => 'Muna Smith',
                'password' => Hash::make('password'),
                'employee_id' => 1,
                'role_id' => 2, // HR
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Deni Wijaya',
                'password' => Hash::make('password'),
                'employee_id' => 2,
                'role_id' => 3, // Employee
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Presences
        $presences = [];
        foreach ([2025, 2026] as $year) {
            for ($month = 1; $month <= 12; $month++) {
                $daysInMonth = rand(5, 20);
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($year, $month, rand(1, 28))->format('Y-m-d');
                    $hour = rand(7, 9);
                    $minute = rand(0, 59);
                    $checkIn = Carbon::parse($date)->setTime($hour, $minute)->format('Y-m-d H:i:s');
                    $checkOut = Carbon::parse($checkIn)->addHours(9)->format('Y-m-d H:i:s');

                    foreach ([1, 2, 3] as $employeeId) {
                        $statuses = [
                            'present' => 70,
                            'absent' => 15,
                            'late' => 10,
                            'leave' => 5,
                        ];
                        $status = $faker->randomElement(array_merge(
                            array_fill(0, $statuses['present'], 'present'),
                            array_fill(0, $statuses['absent'], 'absent'),
                            array_fill(0, $statuses['late'], 'late'),
                            array_fill(0, $statuses['leave'], 'leave'),
                        ));

                        $presences[] = [
                            'employee_id' => $employeeId,
                            'check_in' => in_array($status, ['present', 'late']) ? $checkIn : null,
                            'check_out' => in_array($status, ['present', 'late']) ? $checkOut : null,
                            'date' => $date,
                            'status' => $status,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }
        DB::table('presences')->insert($presences);
    }
}
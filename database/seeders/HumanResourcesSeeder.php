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
            ['id' => 1],
            [
                'name' => 'HR',
                'description' => 'Department Human Resources',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('departments')->updateOrInsert(
            ['id' => 2],
            [
                'name' => 'IT',
                'description' => 'Department Information Technology',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('departments')->updateOrInsert(
            ['id' => 3],
            [
                'name' => 'Sales',
                'description' => 'Department Sales',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );



        // Roles
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            [
                'title' => 'Super Admin',
                'description' => 'Full system access',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            [
                'title' => 'HR Manager',
                'description' => 'Manage employees & payroll',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 3],
            [
                'title' => 'Employee',
                'description' => 'Self-service access',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


        // Employees
        DB::table('employees')->updateOrInsert(
            ['id' => 1],
            [
                'fullname' => 'John Doe',
                'email' => 'superadmin@example.com',
                'department_id' => 2, // IT
                'role_id' => 1,       // Super Admin
                'status' => 'active',
                'salary' => 12000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('employees')->updateOrInsert(
            ['id' => 2],
            [
                'fullname' => 'Muna Smith',
                'email' => 'hr@example.com',
                'department_id' => 1, // HR
                'role_id' => 2,       // HR Manager
                'status' => 'active',
                'salary' => 8000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('employees')->updateOrInsert(
            ['id' => 3],
            [
                'fullname' => 'Deni',
                'email' => 'employee@example.com',
                'department_id' => 3, // Sales
                'role_id' => 3,       // Employee
                'status' => 'active',
                'salary' => 4000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );



        // Users
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'John Doe',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'employee_id' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['id' => 2],
            [
                'name' => 'Muna Smith',
                'email' => 'hr@example.com',
                'password' => Hash::make('password'),
                'employee_id' => 2,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['id' => 3],
            [
                'name' => 'Deni',
                'email' => 'employee@example.com',
                'password' => Hash::make('password'),
                'employee_id' => 3,
                'role_id' => 3,
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
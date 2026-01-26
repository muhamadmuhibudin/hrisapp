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
                'title' => 'HR Manager',
                'description' => 'Manage employees & payroll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Super Admin',
                'description' => 'Full system access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Employee',
                'description' => 'Self-service access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Employees
        DB::table('employees')->insert([
            [
                'fullname' => 'Muna Smith',
                'email' => 'test@example.com',
                'phone_number' => '081234567890',
                'address' => 'Jl. Mawar No. 1',
                'birth_date' => '1990-01-01',
                'hire_date' => now(),
                'department_id' => 1,
                'role_id' => 1,
                'status' => 'active',
                'salary' => 8000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fullname' => 'Deni Wijaya',
                'email' => 'employee@example.com',
                'phone_number' => '082233445566',
                'address' => 'Jl. Melati No. 2',
                'birth_date' => '1995-02-02',
                'hire_date' => now(),
                'department_id' => 3,
                'role_id' => 3,
                'status' => 'active',
                'salary' => 4000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fullname' => 'Dr. John Doe',
                'email' => 'superadmin@example.com',
                'phone_number' => '082737384937',
                'address' => 'Wall Street 128',
                'birth_date' => '1985-03-03',
                'hire_date' => now(),
                'department_id' => 2,
                'role_id' => 2,
                'status' => 'active',
                'salary' => 12000000,
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
        $presences = [];

        for ($month = 1; $month <= 12; $month++) {
            for ($day = 1; $day <= 10; $day++) {
                $date = Carbon::create(2026, $month, rand(1, 28))->format('Y-m-d');

                $hour = rand(7, 9);
                $minute = rand(0, 59);

                $checkIn = Carbon::parse($date)->setTime($hour, $minute)->format('Y-m-d H:i:s');
                $checkOut = Carbon::parse($checkIn)->addHours(9)->format('Y-m-d H:i:s');

                foreach ([1, 2, 3] as $employeeId) {
                    $presences[] = [
                        'employee_id' => $employeeId,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'date' => $date,
                        'status' => 'present',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('presences')->insert($presences);


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

        // Users
        DB::table('users')->updateOrInsert(
            ['email' => 'hr@example.com'],
            [
                'name' => 'Muna Smith',
                'password' => Hash::make('password'),
                'employee_id' => 1,
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
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Dr. John Doe',
                'password' => Hash::make('password'),
                'employee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

    }
}

/*
 // Employees (faker version, for reference)
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
*/
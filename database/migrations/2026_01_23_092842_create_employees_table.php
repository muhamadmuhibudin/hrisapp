<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address');
            $table->date('birth_date');
            $table->date('hire_date');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('status');
            $table->decimal('salary', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::dropIfExists('employees');
    }
};
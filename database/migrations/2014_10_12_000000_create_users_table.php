<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'job_poster', 'freelancer'])->default('freelancer');
            $table->string('phone', 20)->nullable()->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('cid_number', 20)->nullable()->unique()->comment('Citizen Identity Document number');
            $table->string('brn_number', 30)->nullable()->unique()->comment('Business Registration Number');
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'unverified'])->default('unverified');
            $table->string('avatar')->nullable();
            $table->string('preferred_language', 10)->default('en')->comment('en or dz (Dzongkha)');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])->default('active');
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
            $table->index('verification_status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

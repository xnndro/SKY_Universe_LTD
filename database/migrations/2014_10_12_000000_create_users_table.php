<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dating_code')->nullable();            
            $table->date('birthday');
            $table->string('gender');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('profile_picture')->nullable();
            $table->string('banned')->default('no');
            $table->unsignedBigInteger('role_id')->default(2);
            $table->string('user_dating_id')->unique()->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
        });

        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'dating_code' => null,
                'birthday' => '1990-01-01',
                'gender' => 'Male',
                'email' => 'admin@sky.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'phone' => '1234567890',
                'profile_picture' => null,
                'banned' => 'no',
                'role_id' => 1
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

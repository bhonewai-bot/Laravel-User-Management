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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 100)->unique();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('address', 255)->nullable();
            $table->string('password', 255);
            $table->boolean('gender')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};

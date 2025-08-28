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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('event'); // e.g. created, updated, deleted
            $table->string('module')->nullable(); // e.g. products, orders
            $table->text('description')->nullable(); // Details of the action
            $table->json('properties')->nullable(); // Extra data (before/after changes)
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable(); // Browser/device
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

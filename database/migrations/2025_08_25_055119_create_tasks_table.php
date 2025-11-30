<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('user_id');
            $table->string('task_title', 50)->nullable();
            $table->string('task_description', 255)->nullable();

            // Deadline chỉ lưu ngày
            $table->date('task_deadline')->nullable();

            // Priority: low, medium, high
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
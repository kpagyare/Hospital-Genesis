<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->unique(); // e.g. DOC-0001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('specialization');
            $table->string('qualification')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->json('available_days')->nullable(); // ['Monday','Tuesday',...]
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->enum('status', ['active', 'on_leave', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

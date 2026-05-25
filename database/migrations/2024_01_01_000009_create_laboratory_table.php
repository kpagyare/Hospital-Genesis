<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_test_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_code')->unique(); // e.g. LAB-0001
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('lab_test_categories')->onDelete('set null');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('normal_range')->nullable();
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->string('result_id')->unique(); // e.g. RES-0001
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_test_id')->constrained('lab_tests')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->date('test_date');
            $table->string('result_value')->nullable();
            $table->text('remarks')->nullable();
            $table->string('report_file')->nullable(); // PDF upload
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_results');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('lab_test_categories');
    }
};

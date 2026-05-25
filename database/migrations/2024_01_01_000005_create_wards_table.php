<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ward_type')->nullable(); // General, ICU, Maternity, Pediatric, etc.
            $table->integer('total_beds')->default(0);
            $table->decimal('bed_charge_per_day', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number');
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->enum('bed_type', ['general', 'semi_private', 'private', 'icu'])->default('general');
            $table->decimal('charge_per_day', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
        Schema::dropIfExists('wards');
    }
};

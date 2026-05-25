<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_id')->unique(); // e.g. MED-0001
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('medicine_categories')->onDelete('set null');
            $table->string('generic_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('type')->nullable(); // tablet, syrup, injection, etc.
            $table->string('unit')->default('piece'); // piece, ml, mg
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_alert')->default(10);
            $table->date('expiry_date')->nullable();
            $table->string('manufacturer')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('medicine_categories');
    }
};

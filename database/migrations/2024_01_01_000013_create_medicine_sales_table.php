<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_id')->unique(); // e.g. SAL-0001
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('prescription_id')->nullable()->constrained()->onDelete('set null');
            $table->date('sale_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'mobile_money'])->default('cash');
            $table->foreignId('sold_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('medicine_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('medicine_sales')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_sale_items');
        Schema::dropIfExists('medicine_sales');
    }
};

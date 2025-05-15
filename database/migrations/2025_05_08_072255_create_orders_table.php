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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->dateTime('order_date')->nullable(false);
            $table->decimal('total_amount', 10, 2)->nullable(false);
            $table->string('payment_method', 20)->nullable(false);
            $table->string('status', 20)->default('Completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

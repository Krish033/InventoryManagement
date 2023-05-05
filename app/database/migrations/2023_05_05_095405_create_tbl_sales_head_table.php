<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_sales_head', function (Blueprint $table) {
            $table->string('tranNo')->primary();
            $table->date('tranDate')->default('2023-05-03');
            $table->string('customerId', 50);
            $table->string('mop')->nullable();
            $table->double('taxable')->default(0);
            $table->double('taxAmount')->default(0);
            $table->double('totalAmount')->default(0);
            $table->double('paidAmount')->default(0);
            $table->double('balanceAmount')->default(0);
            $table->enum('paymentStatus', ['paid', 'incomplete', 'partially paid', 'cancelled'])->default('incomplete');
            $table->string('createdBy');
            $table->string('updatedBy')->nullable();
            $table->string('deletedBy')->nullable();
            $table->boolean('dFlag')->default(false);
            $table->timestamp('createdAt')->default(now());
            $table->timestamp('updatedAt')->nullable();
            $table->timestamp('deletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tbl_sales_head');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_purchase_head', function (Blueprint $table) {
            $table->string('tranNo')->primary();
            $table->timestamp('tranDate')->default(now());
            $table->string('invoiceNo', 50);
            $table->string('supplierId', 50);
            $table->string('mop', 50);
            $table->double('taxable')->default(0.00);
            $table->double('taxAmount')->default(0.00);
            $table->double('TotalAmount')->default(0.00);
            $table->double('paidAmount')->default(0.00);
            $table->double('balanceAmount')->default(0.00);
            $table->string('createdBy');
            $table->string('updatedBy')->nullable();
            $table->string('deletedBy')->nullable();
            $table->boolean('dflag')->default(false);
            $table->timestamps();
            $table->timestamp('deletedOn')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tbl_purchase_head');
    }
};
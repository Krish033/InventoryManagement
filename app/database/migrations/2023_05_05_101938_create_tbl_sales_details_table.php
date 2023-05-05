<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_sales_details', function (Blueprint $table) {
            $table->string('detailId')->primary();
            $table->string('tranNo', 50);
            $table->string('categoryId', 50);
            $table->string('subCategoryId', 50);
            $table->string('productId', 50);
            $table->double('price')->default(0);
            $table->string('quantity');
            $table->double('amount')->default(0);
            $table->enum('taxType', ['includes', 'excludes'])->default('includes');
            $table->string('taxPercentage');
            $table->double('taxable')->default(0);
            $table->double('taxAmount')->default(0);
            $table->double('subtotal')->default(0);
            $table->longText('description')->nullable();
            $table->timestamp('createdAt')->default(now());
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tbl_sales_details');
    }
};
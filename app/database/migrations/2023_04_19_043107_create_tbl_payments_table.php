<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_payments', function (Blueprint $table) {
            $table->string('pyid')->primary();
            $table->date('date'); // when the purchase was made
            $table->longText('description')->nullable(); // small description about payment
            $table->string('category'); // income|expense
            $table->string('amount')->nullable();
            $table->string('payment_type');
            $table->string('completed')->default(false);
            $table->string('supplier_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('dflag')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tbl_payments');
    }
};
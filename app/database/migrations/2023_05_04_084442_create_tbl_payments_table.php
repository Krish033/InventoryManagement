<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payments', function (Blueprint $table) {
            $table->string('pyid')->primary();
            $table->date('date');
            $table->longText('description')->nullable();
            $table->string('category');
            $table->string('amount')->nullable();
            $table->string('payment_type');
            $table->string('completed')->default('0');
            $table->string('supplier_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('dflag')->default('0');
            $table->timestamps();
            $table->string('reference_id')->nullable();
            $table->string('tax_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_payments');
    }
};

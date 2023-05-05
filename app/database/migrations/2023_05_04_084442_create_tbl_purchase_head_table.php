<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_purchase_head', function (Blueprint $table) {
            $table->string('tranNo')->primary();
            $table->date('tranDate')->default('2023-05-03');
            $table->string('invoiceNo', 50);
            $table->string('supplierId', 50);
            $table->string('mop', 50);
            $table->double('taxable')->default(0);
            $table->double('taxAmount')->default(0);
            $table->double('totalAmount')->default(0);
            $table->double('paidAmount')->default(0);
            $table->double('balanceAmount')->default(0);
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
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_purchase_head');
    }
};
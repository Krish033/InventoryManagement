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
        Schema::create('tbl_sales_items', function (Blueprint $table) {
            $table->string('siId')->primary();
            $table->string('saId');
            $table->string('amount');
            $table->boolean('dflag')->default(false);
            $table->timestamps();
            $table->string('sale_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->date('date')->default('2023-05-03');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_sales_items');
    }
};

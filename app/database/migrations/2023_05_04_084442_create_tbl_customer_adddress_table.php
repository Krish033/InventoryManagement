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
        Schema::create('tbl_customer_adddress', function (Blueprint $table) {
            $table->string('SLNO', 50)->primary();
            $table->string('CustomerID', 50)->nullable();
            $table->string('AddressType', 1)->nullable()->default('1')->comment('1- Billing , 2-Shipping');
            $table->string('Title', 100)->nullable();
            $table->text('Address')->nullable();
            $table->string('CountryID', 50)->nullable();
            $table->string('StateID', 50)->nullable();
            $table->string('CityID', 50)->nullable();
            $table->string('PostalCodeID', 50)->nullable();
            $table->integer('isDefault')->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customer_adddress');
    }
};

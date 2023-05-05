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
        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->string('CID')->primary();
            $table->string('CName');
            $table->string('CImg');
            $table->string('Email');
            $table->string('Address');
            $table->string('CityID');
            $table->string('StateID');
            $table->string('CountryID');
            $table->string('MobileNumber');
            $table->string('ActiveStatus');
            $table->string('DFlag');
            $table->string('CreatedBy');
            $table->string('UpdatedBy');
            $table->string('DeletedBy');
            $table->string('CreatedOn');
            $table->string('UpdatedOn');
            $table->string('DeletedOn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_customer');
    }
};
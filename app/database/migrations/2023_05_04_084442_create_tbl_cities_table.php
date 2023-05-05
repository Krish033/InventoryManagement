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
        Schema::create('tbl_cities', function (Blueprint $table) {
            $table->string('CityID', 50)->primary();
            $table->string('CityName', 30);
            $table->string('StateID', 50);
            $table->string('CountryID', 50)->nullable();
            $table->string('PostalCodeID', 50)->nullable();
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['CityID', 'StateID', 'PostalCodeID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'CityID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cities');
    }
};

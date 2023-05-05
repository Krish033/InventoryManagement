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
        Schema::create('tbl_countries', function (Blueprint $table) {
            $table->string('CountryID', 50)->primary();
            $table->string('sortname', 3);
            $table->string('CountryName', 150);
            $table->integer('PhoneCode');
            $table->string('PhoneLength', 20)->default('0');
            $table->string('CurrencyID', 50)->nullable();
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['CountryID', 'PhoneCode', 'CurrencyID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'CountryID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_countries');
    }
};

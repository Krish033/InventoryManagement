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
        Schema::create('tbl_user_info', function (Blueprint $table) {
            $table->string('UserID', 50)->primary();
            $table->string('Name')->nullable();
            $table->string('FirstName', 50)->nullable();
            $table->string('LastName', 50)->nullable();
            $table->date('DOB')->nullable();
            $table->string('GenderID', 50)->nullable();
            $table->text('Address')->nullable();
            $table->string('CityID', 50)->nullable();
            $table->string('StateID', 50)->nullable();
            $table->string('CountryID', 50)->nullable();
            $table->string('EMail', 150)->nullable();
            $table->string('MobileNumber', 20)->nullable();
            $table->text('ProfileImage')->nullable();
            $table->date('DOJ')->nullable();
            $table->string('PostalCode', 100)->nullable();
            $table->string('PostalCodeID', 100)->nullable();
            $table->integer('ActiveStatus')->nullable()->default(1);
            $table->integer('DFlag')->nullable()->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['UserID', 'GenderID', 'CityID', 'StateID', 'CountryID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'UserID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_info');
    }
};

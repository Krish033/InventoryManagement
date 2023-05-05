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
        Schema::create('tbl_postalcodes', function (Blueprint $table) {
            $table->string('PID', 50)->primary();
            $table->string('PostalCode', 20)->nullable();
            $table->string('CountryID', 50)->nullable();
            $table->string('StateID', 50)->nullable();
            $table->integer('ActiveStatus')->nullable()->default(1);
            $table->integer('DFlag')->nullable()->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['PID', 'CountryID', 'StateID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'PID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_postalcodes');
    }
};

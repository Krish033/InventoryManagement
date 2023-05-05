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
        Schema::create('tbl_banklist', function (Blueprint $table) {
            $table->string('SLNO', 50)->primary();
            $table->string('TypeOfBank', 20)->nullable();
            $table->string('NameOfBanks', 100)->nullable();
            $table->string('CountriesOrStates', 19)->nullable();
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedON')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['SLNO', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'SLNO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_banklist');
    }
};

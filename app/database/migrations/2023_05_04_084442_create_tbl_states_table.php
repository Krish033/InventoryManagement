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
        Schema::create('tbl_states', function (Blueprint $table) {
            $table->string('StateID', 50)->primary();
            $table->string('StateName', 30);
            $table->string('CountryID', 50);
            $table->string('StateCode_UnderGST', 100);
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['StateID', 'CountryID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'StateID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_states');
    }
};

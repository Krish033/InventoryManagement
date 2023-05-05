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
        Schema::create('tbl_bank_account_type', function (Blueprint $table) {
            $table->string('SLNO', 50)->primary();
            $table->string('AccountType', 200)->nullable();
            $table->integer('ActiveStatus')->nullable()->default(1);
            $table->integer('DFlag')->nullable()->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->string('CreatedBy', 50)->nullable();
            $table->timestamp('UpdatedOn')->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('DeletedBy', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bank_account_type');
    }
};

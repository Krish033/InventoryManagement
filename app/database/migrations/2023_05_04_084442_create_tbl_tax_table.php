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
        Schema::create('tbl_tax', function (Blueprint $table) {
            $table->string('TaxID', 50)->primary();
            $table->string('TaxName', 50);
            $table->string('TaxPercentage', 150);
            $table->integer('DFlag')->default(0);
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->integer('ActiveStatus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tax');
    }
};

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
        Schema::create('tbl_docnum', function (Blueprint $table) {
            $table->integer('SLNO', true);
            $table->string('DocType', 50)->nullable();
            $table->string('Prefix', 5)->nullable();
            $table->integer('Length')->nullable();
            $table->integer('CurrNum')->nullable();
            $table->string('Suffix', 10)->nullable();
            $table->string('Year', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_docnum');
    }
};

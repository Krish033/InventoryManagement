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
        Schema::create('tbl_cruds', function (Blueprint $table) {
            $table->string('MID', 50)->index('MID');
            $table->integer('add')->nullable()->default(0);
            $table->integer('view')->nullable()->default(0);
            $table->integer('edit')->nullable()->default(0);
            $table->integer('delete')->nullable()->default(0);
            $table->integer('copy')->nullable()->default(0);
            $table->integer('excel')->nullable()->default(0);
            $table->integer('csv')->nullable()->default(0);
            $table->integer('print')->nullable()->default(0);
            $table->integer('pdf')->default(0);
            $table->integer('restore')->default(0);
            $table->integer('approval')->default(0);
            $table->integer('showpwd')->default(0);

            $table->primary(['MID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cruds');
    }
};

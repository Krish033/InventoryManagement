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
        Schema::create('tbl_user_theme', function (Blueprint $table) {
            $table->integer('SLNO', true);
            $table->string('UserID', 50)->nullable()->index('UserID');
            $table->string('Theme_option', 200)->nullable();
            $table->text('Theme_Value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_theme');
    }
};

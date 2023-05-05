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
        Schema::create('tbl_settings', function (Blueprint $table) {
            $table->integer('SLNO', true);
            $table->text('KeyName')->nullable();
            $table->text('KeyValue')->nullable();
            $table->string('SType', 20)->default('');
            $table->text('Description')->nullable();
            $table->timestamp('UpdatedOn')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_settings');
    }
};

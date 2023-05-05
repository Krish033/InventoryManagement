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
        Schema::create('tbl_purchases', function (Blueprint $table) {
            $table->string('puid')->primary();
            $table->string('name');
            $table->date('date')->default('2023-05-03');
            $table->boolean('is_active');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('dflag')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->string('tax_id')->nullable();
            $table->boolean('auto_update_payment')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_purchases');
    }
};

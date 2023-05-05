<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_suppliers', function (Blueprint $table) {
            $table->string('sid', 50)->primary();
            $table->string('img')->nullable();
            $table->string('name', 200);
            $table->string('address');
            $table->string('email');
            $table->string('countryId');
            $table->string('stateId');
            $table->string('cityId');
            $table->string('phone');
            $table->integer('is_active')->default(1);
            $table->integer('dflag')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_suppliers');
    }
};
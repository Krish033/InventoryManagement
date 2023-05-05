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
        Schema::create('tbl_sales', function (Blueprint $table) {
            $table->string('saId')->primary();
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->string('completed')->default('0');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->boolean('dflag')->default(false);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->string('tax_id')->nullable();
            $table->boolean('auto_update_payment')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_sales');
    }
};
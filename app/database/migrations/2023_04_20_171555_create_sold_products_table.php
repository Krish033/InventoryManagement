<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tbl_sold_products', function (Blueprint $table) {
            $table->id();
            $table->string('pid');
            $table->string('siId');
            $table->string('salesRate');
            $table->string('quantity');
            $table->boolean('dflag')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tbl_sold_products');
    }
};
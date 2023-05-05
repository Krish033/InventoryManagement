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
        Schema::create('tbl_products', function (Blueprint $table) {
            $table->string('pid')->primary();
            $table->string('name');
            $table->string('img');
            $table->string('maxQuantity');
            $table->string('minQuantity');
            $table->string('purchaseRate');
            $table->string('salesRate');
            $table->string('hsn_sac_code');
            $table->string('categoryId');
            $table->string('subCategoryId');
            $table->string('taxId');
            $table->boolean('dflag')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('created_by');
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
        Schema::dropIfExists('tbl_products');
    }
};
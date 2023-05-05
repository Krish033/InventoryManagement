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
        Schema::create('tbl_user_roles', function (Blueprint $table) {
            $table->string('RoleID', 50)->primary();
            $table->string('RoleName', 150)->nullable();
            $table->text('CRUD')->nullable();
            $table->integer('isShow')->nullable()->default(1);
            $table->integer('isNoLogin')->default(0);
            $table->integer('ActiveStatus')->nullable()->default(1);
            $table->integer('DFlag')->nullable()->default(0);
            $table->timestamp('CreatedOn')->useCurrent();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();

            $table->index(['RoleID', 'CreatedBy', 'UpdatedBy', 'DeletedBy'], 'RoleID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_user_roles');
    }
};
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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('UserID', 50);
            $table->string('name', 200);
            $table->string('email', 150);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);
            $table->string('Password1', 150);
            $table->string('RoleID', 50);
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(1);
            $table->integer('isShow')->default(1);
            $table->integer('isLogin')->default(1);
            $table->rememberToken();
            $table->string('CreatedBy', 50)->nullable();
            $table->string('UpdatedBy', 50)->nullable();
            $table->string('DeletedBy', 50)->nullable();
            $table->timestamp('CreatedOn')->nullable();
            $table->timestamp('UpdatedOn')->nullable();
            $table->timestamp('DeletedOn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
};
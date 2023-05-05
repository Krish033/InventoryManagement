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
        Schema::create('tbl_menus', function (Blueprint $table) {
            $table->string('MID', 50)->primary();
            $table->string('Slug', 150)->nullable()->index('Slug');
            $table->string('MenuName', 150)->nullable();
            $table->string('ActiveName', 150)->nullable();
            $table->text('Icon')->nullable();
            $table->text('PageUrl')->nullable();
            $table->string('ParentID', 50)->nullable()->index('ParentID');
            $table->string('Level', 10)->nullable();
            $table->integer('hasSubMenu')->nullable();
            $table->integer('Ordering')->nullable();
            $table->integer('isCheckSetting')->default(0);
            $table->string('SettingsName', 100)->nullable();
            $table->integer('DefaultOrdering')->nullable();
            $table->integer('ActiveStatus')->default(1);
            $table->integer('DFlag')->default(0);
            $table->timestamp('UpdatedOn')->useCurrent();
            $table->string('UpdatedBy', 50)->nullable();

            $table->index(['MID'], 'MID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_menus');
    }
};

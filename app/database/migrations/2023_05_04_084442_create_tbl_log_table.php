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
        Schema::create('tbl_log', function (Blueprint $table) {
            $table->string('LogID', 50)->primary();
            $table->string('ReferID', 50)->index('ReferID');
            $table->string('Description')->nullable();
            $table->string('ModuleName', 150)->nullable();
            $table->string('Action', 100)->nullable();
            $table->text('OldData')->nullable();
            $table->text('NewData')->nullable();
            $table->string('IPAddress', 100)->nullable();
            $table->string('UserID', 50)->nullable()->index('UserID');
            $table->timestamp('LogTime')->useCurrent();

            $table->index(['LogID'], 'LogID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_log');
    }
};

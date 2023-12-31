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
        Schema::table('segsolicitudes_aclaracion', function (Blueprint $table) {
            $table->decimal('monto_promocion',19,2)->nullable();
            $table->string('promocion', 70)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segsolicitudes_aclaracion', function (Blueprint $table) {
            //
        });
    }
};

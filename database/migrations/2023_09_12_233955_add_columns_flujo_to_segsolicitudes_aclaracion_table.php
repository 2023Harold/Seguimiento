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
            $table->string('constancia', 100)->nullable();
            $table->string('fase_autorizacion', 50)->nullable();
            $table->string('nivel_autorizacion', 5)->nullable();
            $table->string('concluido',2)->default('No');
            $table->decimal('monto_solventado',11,2)->nullable();
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
            $table->dropColumns(['constancia','fase_autorizacion','nivel_autorizacion','concluido','monto_solventado']);
        });
    }
};

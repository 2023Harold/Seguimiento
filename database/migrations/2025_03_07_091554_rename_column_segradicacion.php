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
        Schema::table('segradicacion', function (Blueprint $table) {
            $table->renameColumn('fecha_cierre_auditoria', 'acta_cierre_auditoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segradicacion', function (Blueprint $table) {
            $table->renameColumn('acta_cierre_auditoria', 'fecha_cierre_auditoria');
        });
    }
};

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
        Schema::table('segturno_acuse_envio_archivo', function (Blueprint $table) {
            
            $table->string('numero_turno_archivo', 100)->nullable()->change();
            $table->string('turno_archivo',100)->nullable()->change();
            $table->foreignId('auditoria_id')->constrained('segauditorias')->nullable()->change();

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segturno_acuse_envio_archivo', function (Blueprint $table) {
            //
        });
    }
};

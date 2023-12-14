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
        Schema::table('segpras', function (Blueprint $table) {
            $table->string('oficio_contestacion',100)->nullable();
            $table->date('fecha_acuse_contestacion')->nullable();
            $table->string('estatus_cumplimiento',15)->nullable();
            $table->text('conlusion_pras')->nullable();
            $table->string('oficio_medida_apremio',100)->nullable();
            $table->date('fecha_acuse_medida_apremio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segpras', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::create('seglistadoentidades', function (Blueprint $table) {
            $table->id();
            $table->string('no_auditoria',150);
            $table->string('entidades', 150);
            $table->string('textos_doc', 150);
            $table->integer('cuenta_publica');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seglistadoentidades');
    }
};

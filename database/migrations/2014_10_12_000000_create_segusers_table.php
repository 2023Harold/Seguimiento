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
        Schema::create('segusers', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_plataforma_id')->nullable();
            $table->string('name',100);
            $table->string('curp', 18)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('puesto', 150);
            $table->unsignedInteger('unidad_administrativa_id')->nullable();
            $table->string('siglas_rol',5)->nullable();
            $table->string('estatus', 10)->default('Activo');
            $table->dateTime('fecha_ultimo_acceso')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('usuario_creacion_id')->nullable()->constrained('segusers')->default(1);
            $table->foreignId('usuario_actualizacion_id')->nullable()->constrained('segusers');
            $table->rememberToken();
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
        Schema::dropIfExists('segusers');
    }
};

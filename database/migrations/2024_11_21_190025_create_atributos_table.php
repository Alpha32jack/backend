<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtributosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atributos', function (Blueprint $table) {
            $table->id(); // ID único para el atributo
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Relación con estudiantes
            $table->string('name'); // Nombre del personaje
            $table->integer('armor_class_base')->nullable(); 
            $table->integer('armor_class_constitution')->nullable(); 
            $table->integer('armor_class_items')->nullable(); 
            $table->integer('armor_class_total')->nullable(); 
            $table->integer('dodge_base')->nullable(); 
            $table->integer('dodge_destreza')->nullable(); 
            $table->integer('dodge_items')->nullable(); 
            $table->integer('dodge_total')->nullable(); 
            $table->integer('hit_points_base')->nullable(); 
            $table->integer('damage_taken')->nullable(); 
            $table->integer('total_life')->nullable(); 
            $table->integer('level')->nullable(); 
            $table->integer('experience')->nullable(); 
            $table->string('nickname')->nullable(); 
            $table->string('characteristic')->nullable(); 
            $table->integer('bonus_item')->nullable(); 
            $table->string('skill')->nullable(); 
            $table->integer('total_skill')->nullable(); 
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atributos');
    }
}

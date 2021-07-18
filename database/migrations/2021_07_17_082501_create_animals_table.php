<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы существующих животных.
 */
class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('x')->comment('Координата x');
            $table->unsignedInteger('y')->comment('Координата y');
            $table->unsignedInteger('type_id')->comment('Идентификатор типа животного');
            $table->unsignedInteger('game_field_id')->comment('Идентификатор игрового поля');

            $table->foreign('type_id')->references('id')->on('animal_types');
            $table->foreign('game_field_id')->references('id')->on('game_fields');
        });

        DB::select(DB::raw("COMMENT ON TABLE animals IS 'Таблица существующих животных'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}

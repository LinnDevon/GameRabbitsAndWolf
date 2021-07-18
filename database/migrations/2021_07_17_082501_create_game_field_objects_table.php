<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы существующих животных.
 */
class CreateGameFieldObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_field_objects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('x')->comment('Координата x');
            $table->unsignedInteger('y')->comment('Координата y');
            $table->unsignedInteger('type_id')->comment('Идентификатор типа животного');
            $table->unsignedInteger('game_field_id')->comment('Идентификатор игрового поля');

            $table->foreign('type_id')->references('id')->on('object_types');
            $table->foreign('game_field_id')->references('id')->on('game_fields');
        });

        DB::select(DB::raw("COMMENT ON TABLE game_field_objects IS 'Таблица объектов игрового поля'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_field_objects');
    }
}

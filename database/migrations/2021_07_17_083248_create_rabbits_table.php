<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы существующих зайцев.
 */
class CreateRabbitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rabbits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('x')->comment('Координата x');
            $table->unsignedInteger('y')->comment('Координата y');
            $table->unsignedInteger('game_field_id')->comment('Идентификатор игрового поля');

            $table->foreign('game_field_id')->references('id')->on('game_fields');
        });

        DB::select(DB::raw("COMMENT ON TABLE rabbits IS 'Таблица существующих зайцев'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rabbits');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы волков.
 */
class CreateWolvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wolves', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('object_id')->comment('Идентификатор объекта на игровом поле');
            $table->tinyInteger('is_hungry')->default(1)->comment('Голоден ли волк?');

            $table->foreign('object_id')->references('id')->on('game_field_objects');
        });

        DB::select(DB::raw("COMMENT ON TABLE wolves IS 'Таблица волков'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wolves');
    }
}

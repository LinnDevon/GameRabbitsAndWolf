<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы игрового поля.
 */
class CreateGameFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('height')->comment('Высота поля');
            $table->unsignedInteger('width')->comment('Ширина поля');
            $table->unsignedInteger('count_steps')->comment('Количество оставшихся шагов');
        });

        DB::select(DB::raw("COMMENT ON TABLE game_fields IS 'Таблица игровых полей'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_fields');
    }
}

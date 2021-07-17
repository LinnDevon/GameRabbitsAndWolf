<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы типов животных.
 */
class CreateAnimalTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->comment('Наименование типа животного');
        });

        // Заполняем таблицу
        DB::table('animal_types')->insert(['name' => 'Заяц']);
        DB::table('animal_types')->insert(['name' => 'Волк']);

        DB::select(DB::raw("COMMENT ON TABLE animal_types IS 'Таблица типов животных'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_types');
    }
}

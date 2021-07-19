<?php

use App\Models\ObjectType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция по созданию таблицы типов объектов игрового поля.
 */
class CreateObjectTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10)->comment('Наименование типа объекта игрового поля');
        });

        // Заполняем таблицу
        DB::table('object_types')->insert(['id' => ObjectType::TYPE_RABBIT_ID, 'name' => 'заяц']);
        DB::table('object_types')->insert(['id' => ObjectType::TYPE_WOLF_ID, 'name' => 'волк']);

        DB::select(DB::raw("COMMENT ON TABLE object_types IS 'Таблица типов объектов игрового поля'"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_types');
    }
}

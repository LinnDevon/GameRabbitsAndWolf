<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\GameField;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Класс сервиса игрового поля.
 */
class GameFieldService
{
    /**
     * Метод создания нового игрового поля.
     *
     * @param array $data Данные для создания.
     */
    public static function createGameField(array $data)
    {
        $gameField = new GameField();
        DB::transaction(function () use ($gameField, $data) {
            $gameField->fill($data);
            $gameField->save();
        });
    }

    /**
     * Метод выполнения следующего шага на игровом поле.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @throws Exception
     */
    public static function executeNextStep(int $fieldId)
    {
        /** @var Animal[] $animals */
        $animals = GameField::find($fieldId)->animals;

        DB::transaction(function () use ($animals) {
            foreach ($animals as $animal) {
                $animal->moveRandomStep();
                $animal->save();
            }
        });
    }
}

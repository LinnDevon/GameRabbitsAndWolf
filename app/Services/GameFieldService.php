<?php

namespace App\Services;

use App\Models\GameFieldObject;
use App\Models\GameField;
use Exception;
use Illuminate\Support\Collection;
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
        /** @var GameField $gameField */
        $gameField = GameField::find($fieldId);
        if ($gameField->count_steps === 0) {
            throw new Exception('Больше ходов не осталось. Игра завершена.');
        }

        /** @var Collection|GameFieldObject[] $objects */
        $objects = $gameField->objects;
        DB::transaction(function () use ($objects, $gameField) {
            foreach ($objects as $object) {
                GameFieldObjectService::moveRandomStep($object);
                $object->save();
            }

            $deadObjectIds = GameFieldObjectService::getDeadObjects($gameField);
            GameFieldObjectService::multiplyObjects($objects, $gameField);
            GameFieldObject::destroy($deadObjectIds);
            $gameField->count_steps--;
            $gameField->save();
        });
    }
}

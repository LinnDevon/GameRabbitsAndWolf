<?php

namespace App\Services;

use App\Models\GameFieldObject;
use App\Models\ObjectType;
use App\Models\GameField;
use App\Repositories\GameFieldObjectRepository;
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
                $object->moveRandomStep();
                $object->save();
            }

            $deadObjectIds = self::getDeadObjects($gameField);
            self::multiplyObjects($objects, $gameField);
            GameFieldObject::destroy($deadObjectIds);
            $gameField->count_steps--;
            $gameField->save();
        });
    }

    /**
     * Метод получения объектов на игровом поле, который умрут на текущем шаге.
     *
     * @param GameField $gameField Игровое поле.
     *
     * @return array
     */
    protected static function getDeadObjects(GameField $gameField) : array
    {
        /** @var Collection|GameFieldObject[] $objects */
        $objects = $gameField->objects;
        /** @var Collection|GameFieldObject[] $wolfObjects */
        $wolfObjects = $objects->where('type_id', ObjectType::TYPE_WOLF_ID);
        /** @var Collection|GameFieldObject[] $rabbitObjects */
        $rabbitObjects = $objects->where('type_id', ObjectType::TYPE_RABBIT_ID);

        $deadObjectIds = [];
        foreach ($wolfObjects as $wolfObject) {
            $neighborIds = [];
            foreach ($rabbitObjects as $rabbitObject) {
                if ($rabbitObject->isThisCell($wolfObject->x, $wolfObject->y)) {
                    $deadObjectIds[]             = $rabbitObject->id;
                    $wolfObject->wolf->is_hungry = 0;
                    $wolfObject->wolf->save();
                }

                if ($rabbitObject->isNeighboringCell($wolfObject->x, $wolfObject->y)) {
                    $neighborIds[] = $rabbitObject->id;
                }
            }

            if (count($neighborIds) === 1) {
                $deadObjectIds               = array_merge($deadObjectIds, $neighborIds);
                $wolfObject->wolf->is_hungry = 0;
                $wolfObject->wolf->save();
            }
        }

        if ($gameField->isLastStep()) {
            $deadObjectIds = array_merge($deadObjectIds,
                GameFieldObjectRepository::getHungryWolfIds($gameField->id)->pluck('id')->toArray());
        }

        return $deadObjectIds;
    }

    /**
     * Метод размножения объектов игрового поля.
     *
     * @param Collection|GameFieldObject[] $gameFieldObjects Объекты игрового поля.
     * @param GameField                    $gameField        Игровое поле.
     */
    protected static function multiplyObjects(Collection $gameFieldObjects, GameField $gameField)
    {
        /** @var Collection|GameFieldObject[] $rabbitObjects */
        $rabbitObjects = $gameFieldObjects->where('type_id', ObjectType::TYPE_RABBIT_ID)->sortBy(['x', 'y']);

        $prev       = null;
        $toMultiply = [];
        foreach ($rabbitObjects as $keyObject => $rabbitObject) {
            if (empty($prev)) {
                $prev = $rabbitObject;
                continue;
            }

            if ($rabbitObject->isThisCell($prev->x, $prev->y)) {
                $toMultiply[] = $rabbitObject;
            }

            $prev = $rabbitObject;
        }

        $data = [
            'type_id'       => ObjectType::TYPE_RABBIT_ID,
            'game_field_id' => $gameField->id,
        ];

        $toMultiply = array_unique($toMultiply);
        /** @var GameFieldObject $item */
        foreach ($toMultiply as $item) {
            $coordinates = $item->getDiagonalCoordinates();
            foreach ($coordinates as $coordinate) {
                $data['x'] = $coordinate['x'];
                $data['y'] = $coordinate['y'];

                GameFieldObjectService::createObject($data);
            }
        }
    }
}

<?php

namespace App\Services;

use App\Models\GameFieldObject;
use App\Models\ObjectType;
use App\Models\GameField;
use App\Repositories\GameFieldObjectRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Класс сервиса объектов игрового поля.
 */
class GameFieldObjectService
{
    /**
     * Метод создания нового объекта игрового поля.
     *
     * @param array $data Данные для создания.
     */
    public static function createObject(array $data)
    {
        /** @var GameField $field */
        $field = GameField::find($data['game_field_id']);
        if ($data['x'] < 0 || $data['x'] > $field->width) {
            return;
        }

        if ($data['y'] < 0 || $data['y'] > $field->height) {
            return;
        }

        $object = new GameFieldObject();
        DB::transaction(function () use ($object, $data) {
            if (isset($data['type'])) {
                /** @var ObjectType $objectType */
                $objectType      = ObjectType::query()->where(['name' => $data['type']])->first();
                $data['type_id'] = $objectType->id;
                unset($data['type']);
            }

            $object->fill($data);
            $object->save();
            if ($data['type_id'] === ObjectType::TYPE_WOLF_ID) {
                $object->wolf()->create();
            }
        });
    }

    /**
     * Метод массового создания объектов игрового поля с рандомными координатами.
     *
     * @param array $data Данные для создания.
     *
     * @throws Exception
     */
    public static function createObjectsWithRandomCoordinates(array $data)
    {
        $itemData = [
            'type'          => $data['type'],
            'game_field_id' => $data['game_field_id'],
        ];

        /** @var GameField $gameField */
        $gameField = GameField::find($data['game_field_id']);
        for ($i = 0; $i < $data['count']; $i++) {
            $itemData['x'] = $gameField->getRandomX();
            $itemData['y'] = $gameField->getRandomY();

            self::createObject($itemData);
        }
    }

    /**
     * Метод получения списка всех объектов игрового поля.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @return Collection
     */
    public static function getObjectList(int $fieldId) : Collection
    {
        return GameFieldObjectRepository::getObjectListByFieldId($fieldId);
    }

    /**
     * Метод получения объектов на игровом поле, который умрут на текущем шаге.
     *
     * @param GameField $gameField Игровое поле.
     *
     * @return array
     */
    public static function getDeadObjects(GameField $gameField) : array
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
                if ($rabbitObject->isThisCell($wolfObject)) {
                    $deadObjectIds[]             = $rabbitObject->id;
                    $wolfObject->wolf->is_hungry = 0;
                    $wolfObject->wolf->save();
                }

                if ($rabbitObject->isNeighboringCell($wolfObject)) {
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
    public static function multiplyObjects(Collection $gameFieldObjects, GameField $gameField)
    {
        /** @var Collection|GameFieldObject[] $rabbitObjects */
        $rabbitObjects = $gameFieldObjects->where('type_id', ObjectType::TYPE_RABBIT_ID)->sortBy(['x', 'y']);

        $prev       = null;
        $toMultiply = [];
        foreach ($rabbitObjects as $rabbitObject) {
            if (empty($prev)) {
                $prev = $rabbitObject;
                continue;
            }

            if ($rabbitObject->isThisCell($prev)) {
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

                self::createObject($data);
            }
        }
    }

    /**
     * Сделать рандомный шаг.
     *
     * @param GameFieldObject $object Объект игрового поля.
     *
     * @throws Exception В случае, если походить не удалось.
     */
    public static function moveRandomStep(GameFieldObject $object)
    {
        foreach (Arr::shuffle([0, 1, 2, 3]) as $number) {
            switch ($number) {
                case 0:
                    if (self::moveRight($object)) {
                        return;
                    }

                    break;
                case 1:
                    if (self::moveLeft($object)) {
                        return;
                    }

                    break;
                case 2:
                    if (self::moveDown($object)) {
                        return;
                    }

                    break;
                case 3:
                    if (self::moveUp($object)) {
                        return;
                    }

                    break;
            }
        }

        throw new Exception('На этом поле не разгуляться.');
    }

    /**
     * Сделать шаг вправо.
     *
     * @param GameFieldObject $object Объект игрового поля.
     *
     * @return bool
     */
    public static function moveRight(GameFieldObject $object) : bool
    {
        if ($object->x < $object->game_field->width) {
            $object->x++;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг влево.
     *
     * @param GameFieldObject $object Объект игрового поля.
     *
     * @return bool
     */
    public static function moveLeft(GameFieldObject $object) : bool
    {
        if ($object->x > 0) {
            $object->x--;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг вверх.
     *
     * @param GameFieldObject $object Объект игрового поля.
     *
     * @return bool
     */
    public static function moveUp(GameFieldObject $object) : bool
    {
        if ($object->y < $object->game_field->height) {
            $object->y++;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг вниз.
     *
     * @param GameFieldObject $object Объект игрового поля.
     *
     * @return bool
     */
    public static function moveDown(GameFieldObject $object) : bool
    {
        if ($object->y > 0) {
            $object->y--;

            return true;
        }

        return false;
    }
}

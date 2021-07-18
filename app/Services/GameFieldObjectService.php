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
        $object = new GameFieldObject();
        DB::transaction(function () use ($object, $data) {
            /** @var ObjectType $objectType */
            $objectType      = ObjectType::query()->where(['name' => $data['type']])->first();
            $data['type_id'] = $objectType->id;
            unset($data['type']);

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
}

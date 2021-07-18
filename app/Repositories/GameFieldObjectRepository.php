<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Репозиторий для работы с объектами игрового поля.
 */
class GameFieldObjectRepository
{
    /**
     * Метод получения списка объектов игрвоого поля по идентификатору игрового поля.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @return Collection
     */
    public static function getObjectListByFieldId(int $fieldId) : Collection
    {
        return DB::table('game_field_objects as gfo')
                 ->select(['gfo.id', 'gfo.x', 'gfo.y', 'ot.name as type'])
                 ->join('object_types as ot', 'gfo.type_id', '=', 'ot.id')
                 ->where(['gfo.game_field_id' => $fieldId])
                 ->get();
    }

    /**
     * Метод получения списка идентификаторов голодных волков.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @return Collection
     */
    public static function getHungryWolfIds(int $fieldId) : Collection
    {
        return DB::table('game_field_objects as gfo')
                 ->select(['gfo.id'])
                 ->join('wolves as w', 'w.object_id', '=', 'gfo.id')
                 ->where(['gfo.game_field_id' => $fieldId, 'w.is_hungry' => 1])
                 ->get();
    }
}

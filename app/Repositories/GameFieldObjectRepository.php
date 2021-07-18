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
     */
    public static function getObjectListByFieldId(int $fieldId) : Collection
    {
        return DB::table('game_field_objects as gfo')
                 ->select(['gfo.id', 'gfo.x', 'gfo.y', 'ot.name as type'])
                 ->join('object_types as ot', 'gfo.type_id', '=', 'ot.id')
                 ->where(['gfo.game_field_id' => $fieldId])
                 ->get();
    }
}

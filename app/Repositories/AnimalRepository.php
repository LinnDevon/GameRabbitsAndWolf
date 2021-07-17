<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Репозиторий для работы с животными.
 */
class AnimalRepository
{
    /**
     * Метод получения списка животных по идентификатору игрового поля.
     */
    public static function getAnimalListByFieldId(int $fieldId) : Collection
    {
        return DB::table('animals as a')
                 ->select(['a.id', 'a.x', 'a.y', 'at.name as type'])
                 ->join('animal_types as at', 'a.type_id', '=', 'at.id')
                 ->where(['a.game_field_id' => $fieldId])
                 ->get();
    }
}

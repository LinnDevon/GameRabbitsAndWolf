<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Класс модели типа объекта игрового поля.
 *
 * @property int                    id   Идентификатор типа объекта игрового поля.
 * @property string                 name Наименование типа объекта игрового поля.
 *
 * @property-read GameFieldObject[] objects
 */
class ObjectType extends Model
{
    public const TYPE_RABBIT_ID = 1;
    public const TYPE_WOLF_ID   = 2;

    /**
     * Получить животных.
     *
     * @return HasMany
     */
    public function objects() : HasMany
    {
        return $this->hasMany(GameFieldObject::class);
    }
}

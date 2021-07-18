<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Класс модели волков.
 *
 * @property int id        Идентификатор волка.
 * @property int object_id Идентификатор объекта игрвоого поля.
 * @property int is_hungry Голоден ли волк?
 *
 * @property-read GameFieldObject object
 */
class Wolf extends Model
{
    /**
     * Получить объект игрового поля.
     *
     * @return HasOne
     */
    public function object() : HasOne
    {
        return $this->hasOne(GameFieldObject::class);
    }
}

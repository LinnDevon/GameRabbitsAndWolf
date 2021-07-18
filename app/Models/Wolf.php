<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $fillable = ['object_id', 'is_hungry'];

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Получить объект игрового поля.
     *
     * @return BelongsTo
     */
    public function object() : BelongsTo
    {
        return $this->belongsTo(GameFieldObject::class);
    }
}

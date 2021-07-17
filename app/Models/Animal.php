<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Класс модели существующего животного.
 *
 * @property int             id            Идентификатор существующего животного.
 * @property int             x             Координата x существующего животного.
 * @property int             y             Координата y существующего животного.
 * @property int             type_id       Идентификатор типа животного.
 * @property int             game_field_id Идентификатор игрового поля.
 *
 * @property-read GameField  game_field
 * @property-read AnimalType animal_type
 */
class Animal extends Model
{
    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $fillable = ['x', 'y', 'type_id', 'game_field_id'];

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Получить игровое поле.
     *
     * @return BelongsTo
     */
    public function game_field() : BelongsTo
    {
        return $this->belongsTo(GameField::class);
    }

    /**
     * Получить тип животного.
     *
     * @return BelongsTo
     */
    public function animal_type() : BelongsTo
    {
        return $this->belongsTo(AnimalType::class);
    }
}

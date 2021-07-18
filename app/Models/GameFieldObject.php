<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Класс модели существующего объекта игрового поля.
 *
 * @property int             id            Идентификатор существующего объекта игрового поля.
 * @property int             x             Координата x существующего объекта игрового поля.
 * @property int             y             Координата y существующего объекта игрового поля.
 * @property int             type_id       Идентификатор типа объекта игрового поля.
 * @property int             game_field_id Идентификатор игрового поля.
 *
 * @property-read GameField  game_field
 * @property-read ObjectType object_type
 * @property-read Wolf       wolf
 */
class GameFieldObject extends Model
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
     * Получить тип объекта игрового поля.
     *
     * @return BelongsTo
     */
    public function object_type() : BelongsTo
    {
        return $this->belongsTo(ObjectType::class);
    }

    /**
     * Получить волка.
     *
     * @return HasOne
     */
    public function wolf() : HasOne
    {
        return $this->hasOne(Wolf::class, 'object_id', 'id');
    }

    /**
     * Проверяет, сидят ли объекты игрового поля на соседних клетках.
     *
     * @param GameFieldObject $object Объект игрового поля, с которым сравниваются координаты.
     *
     * @return bool
     */
    public function isNeighboringCell(GameFieldObject $object) : bool
    {
        return abs($this->x - $object->x) + abs($this->y - $object->y) === 1;
    }

    /**
     * Проверяет, сидят ли объекты игрового поля на одной и той же клетке.
     *
     * @param GameFieldObject $object Объект игрового поля, с которым сравниваются координаты.
     *
     * @return bool
     */
    public function isThisCell(GameFieldObject $object) : bool
    {
        return abs($this->x - $object->x) + abs($this->y - $object->y) === 0;
    }

    /**
     * Метод выдачи координат диагональных клеток.
     *
     * @return array
     */
    public function getDiagonalCoordinates() : array
    {
        return [
            [
                'x' => $this->x + 1,
                'y' => $this->y + 1,
            ],
            [
                'x' => $this->x - 1,
                'y' => $this->y + 1,
            ],
            [
                'x' => $this->x + 1,
                'y' => $this->y - 1,
            ],
            [
                'x' => $this->x - 1,
                'y' => $this->y - 1,
            ],
        ];
    }
}

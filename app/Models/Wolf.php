<?php

namespace App\Models;

use App\Traits\MoveTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * Класс модели существующего волка.
 *
 * @property int            id              Идентификатор существующего животного.
 * @property int            x               Координата x существующего животного.
 * @property int            y               Координата y существующего животного.
 * @property int            is_hungry       Голоден ли волк?
 * @property int            game_field_id   Идентификатор игрового поля.
 *
 * @property-read GameField game_field
 */
class Wolf extends Model
{
    use MoveTrait;

    /**
     * Название животного
     */
    public const NAME = 'Волк';

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $fillable = ['x', 'y', 'is_hungry', 'game_field_id'];

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
     * Проверяет, сидят ли животные на соседних клетках.
     *
     * @param int $x Координата x второго животного.
     * @param int $y Координата y второго животного.
     *
     * @return bool
     */
    public function isNeighboringCell(int $x, int $y) : bool
    {
        return abs($this->x - $x) + abs($this->y - $y) === 1;
    }

    /**
     * Проверяет, сидят ли животные на одной и той же клетке.
     *
     * @param int $x Координата x второго животного.
     * @param int $y Координата y второго животного.
     *
     * @return bool
     */
    public function isThisCell(int $x, int $y) : bool
    {
        return abs($this->x - $x) + abs($this->y - $y) === 0;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return self::NAME;
    }
}

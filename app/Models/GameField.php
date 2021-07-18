<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Класс модели игрового поля.
 *
 * @property int                      id          Идентификатор игрового поля.
 * @property int                      height      Длина игрового поля.
 * @property int                      width       Ширина игрового поля.
 * @property int                      count_steps Количество оставшихся шагов.
 *
 * @property-read Collection|Rabbit[] rabbits
 * @property-read Collection|Wolf[]   wolves
 */
class GameField extends Model
{
    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
     * @var array
     */
    protected $fillable = ['height', 'width', 'count_steps'];

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Получить зайцев для игрового поля.
     *
     * @return HasMany
     */
    public function rabbits() : HasMany
    {
        return $this->hasMany(Rabbit::class);
    }

    /**
     * Получить волков для игрового поля.
     *
     * @return HasMany
     */
    public function wolves() : HasMany
    {
        return $this->hasMany(Wolf::class);
    }

    /**
     * Получить рандомную координату x на поле.
     *
     * @return int
     *
     * @throws Exception
     */
    public function getRandomX() : int
    {
        return random_int(0, $this->width);
    }

    /**
     * Получить рандомную координату y на поле.
     *
     * @return int
     *
     * @throws Exception
     */
    public function getRandomY() : int
    {
        return random_int(0, $this->height);
    }
}

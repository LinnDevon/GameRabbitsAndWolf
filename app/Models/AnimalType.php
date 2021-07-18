<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Класс модели типа животного.
 *
 * @property int    id   Идентификатор типа животного.
 * @property string name Наименование типа животного.
 *
 * @property-read Animal[] animals
 */
class AnimalType extends Model
{
    public const TYPE_RABBIT_ID = 1;
    public const TYPE_WOLF_ID = 2;

    /**
     * Получить животных.
     *
     * @return HasMany
     */
    public function animals() : HasMany
    {
        return $this->hasMany(Animal::class);
    }
}

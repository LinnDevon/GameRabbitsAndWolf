<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

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
     * Сделать рандомный шаг.
     *
     * @throws Exception В случае, если походить не удалось.
     */
    public function moveRandomStep()
    {
        foreach (Arr::shuffle([0, 1, 2, 3]) as $number) {
            switch ($number) {
                case 0:
                    if ($this->moveRight()) {
                        return;
                    }

                    break;
                case 1:
                    if ($this->moveLeft()) {
                        return;
                    }

                    break;
                case 2:
                    if ($this->moveDown()) {
                        return;
                    }

                    break;
                case 3:
                    if ($this->moveUp()) {
                        return;
                    }

                    break;
            }
        }

        throw new Exception('На этом поле не разгуляться.');
    }

    /**
     * Сделать шаг вправо.
     *
     * @return bool
     */
    public function moveRight() : bool
    {
        if ($this->x < $this->game_field->width) {
            $this->x++;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг влево.
     *
     * @return bool
     */
    public function moveLeft() : bool
    {
        if ($this->x > 0) {
            $this->x--;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг вверх.
     *
     * @return bool
     */
    public function moveUp() : bool
    {
        if ($this->y < $this->game_field->height) {
            $this->y++;

            return true;
        }

        return false;
    }

    /**
     * Сделать шаг вниз.
     *
     * @return bool
     */
    public function moveDown() : bool
    {
        if ($this->y > 0) {
            $this->y--;

            return true;
        }

        return false;
    }

    /**
     * Проверяет, сидят ли объекты игрового поля на соседних клетках.
     *
     * @param int $x Координата x второго объекта игрового поля.
     * @param int $y Координата y второго объекта игрового поля.
     *
     * @return bool
     */
    public function isNeighboringCell(int $x, int $y) : bool
    {
        return abs($this->x - $x) + abs($this->y - $y) === 1;
    }

    /**
     * Проверяет, сидят ли объекты игрового поля на одной и той же клетке.
     *
     * @param int $x Координата x второго объекта игрового поля.
     * @param int $y Координата y второго объекта игрового поля.
     *
     * @return bool
     */
    public function isThisCell(int $x, int $y) : bool
    {
        return abs($this->x - $x) + abs($this->y - $y) === 0;
    }
}

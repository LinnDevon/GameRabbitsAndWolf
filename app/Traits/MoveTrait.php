<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Arr;

/**
 * Класс трейта для передвижения животных.
 */
trait MoveTrait
{
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
}

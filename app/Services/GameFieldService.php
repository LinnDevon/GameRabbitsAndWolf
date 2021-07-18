<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\GameField;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Класс сервиса игрового поля.
 */
class GameFieldService
{
    /**
     * Метод создания нового игрового поля.
     *
     * @param array $data Данные для создания.
     */
    public static function createGameField(array $data)
    {
        $gameField = new GameField();
        DB::transaction(function () use ($gameField, $data) {
            $gameField->fill($data);
            $gameField->save();
        });
    }

    /**
     * Метод выполнения следующего шага на игровом поле.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @throws Exception
     */
    public static function executeNextStep(int $fieldId)
    {
        /** @var GameField $gameField */
        $gameField = GameField::find($fieldId);
        if ($gameField->count_steps === 0) {
            throw new Exception('Больше ходов не осталось. Игра завершена.');
        }

        /** @var Collection|Animal[] $animals */
        $animals = $gameField->animals;
        DB::transaction(function () use ($animals) {
            foreach ($animals as $animal) {
                $animal->moveRandomStep();
                $animal->save();
            }

            /** @var Collection|Animal[] $wolfs */
            $wolfs = $animals->where('type_id', AnimalType::TYPE_WOLF_ID);
            /** @var Collection|Animal[] $rabbits */
            $rabbits = $animals->where('type_id', AnimalType::TYPE_RABBIT_ID);

            $deadAnimalIds = [];
            foreach ($wolfs as $wolf) {
                $neighborIds = [];
                foreach ($rabbits as $rabbit) {
                    if ($rabbit->isThisCell($wolf->x, $wolf->y)) {
                        $deadAnimalIds[] = $rabbit->id;
                    }

                    if ($rabbit->isNeighboringCell($wolf->x, $wolf->y)) {
                        $neighborIds[] = $rabbit->id;
                    }
                }

                if (count($neighborIds) === 1) {
                    $deadAnimalIds = array_merge($deadAnimalIds, $neighborIds);
                }
            }

            Animal::destroy($deadAnimalIds);
        });
    }
}

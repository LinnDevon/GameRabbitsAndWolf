<?php

namespace App\Services;

use App\Models\GameField;
use App\Models\Rabbit;
use App\Models\Wolf;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Класс сервиса животных.
 */
class AnimalService
{
    /**
     * Метод создания нового животного.
     *
     * @param array $data Данные для создания.
     *
     * @throws Exception
     */
    public static function createAnimal(array $data)
    {
        if ($data['type'] === Wolf::NAME) {
            $animal = new Wolf();
        } elseif ($data['type'] === Rabbit::NAME) {
            $animal = new Rabbit();
        } else {
            throw new Exception('Нет такого животного');
        }
        unset($data['type']);

        DB::transaction(function () use ($animal, $data) {
            $animal->fill($data);
            $animal->save();
        });
    }

    /**
     * Метод массового создания животных с рандомными координатами.
     *
     * @param array $data Данные для создания.
     *
     * @throws Exception
     */
    public static function createAnimalsWithRandomCoordinates(array $data)
    {
        $itemData = [
            'type'          => $data['type'],
            'game_field_id' => $data['game_field_id'],
        ];

        /** @var GameField $gameField */
        $gameField = GameField::find($data['game_field_id']);
        for ($i = 0; $i < $data['count']; $i++) {
            $itemData['x'] = $gameField->getRandomX();
            $itemData['y'] = $gameField->getRandomY();

            AnimalService::createAnimal($itemData);
        }
    }

    /**
     * Метод получения списка всех существующих животных на игровом поле.
     *
     * @param int $fieldId Идентификатор игрового поля.
     *
     * @return Collection
     */
    public static function getAnimalList(int $fieldId) : Collection
    {
        $wolves = Wolf::query()->where('game_field_id', $fieldId)->select(['id', 'x', 'y'])->get()->append(['name']);
        $rabbits = Rabbit::query()->where('game_field_id', $fieldId)->select(['id', 'x', 'y'])->get()->append(['name']);
        return $wolves->merge($rabbits);
    }
}

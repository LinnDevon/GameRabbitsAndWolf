<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\GameField;
use App\Repositories\AnimalRepository;
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
     */
    public static function createAnimal(array $data)
    {
        $animal = new Animal();
        DB::transaction(function () use ($animal, $data) {
            /** @var AnimalType $animalType */
            $animalType      = AnimalType::query()->where(['name' => $data['type']])->first();
            $data['type_id'] = $animalType->id;
            unset($data['type']);

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
        return AnimalRepository::getAnimalListByFieldId($fieldId);
    }

    /**
     * Метод обновления координат животных.
     *
     * @param int $x        Координата x.
     * @param int $y        Координата y.
     * @param int $animalId Идентификатор животного.
     */
    public static function updateCoordinates(int $x, int $y, int $animalId)
    {
        $animal = Animal::find($animalId);
        $animal->x = $x;
        $animal->y = $y;
        $animal->save();
    }
}

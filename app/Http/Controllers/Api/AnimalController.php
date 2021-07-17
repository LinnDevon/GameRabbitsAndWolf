<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnimalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Класс контроллера животных.
 */
class AnimalController extends Controller
{
    /**
     * Метод создания новой игры.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createAnimal(Request $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        AnimalService::createAnimal($data);
    }

    /**
     * Метод создания новой игры.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createAnimalsWithRandomCoordinates(Request $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        AnimalService::createAnimalsWithRandomCoordinates($data);
    }

    /**
     * Метод получения списка всех существующих животных на игровом поле.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @return Collection
     */
    public function getAnimalList(Request $request, int $fieldId) : Collection
    {
        return AnimalService::getAnimalList($fieldId);
    }
}

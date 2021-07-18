<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAnimalRequest;
use App\Http\Requests\AddAnimalsWithRandomCoordinatesRequest;
use App\Http\Requests\GetAnimalsRequest;
use App\Services\GameFieldObjectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Класс контроллера объектов игрового поля.
 */
class GameFieldObjectController extends Controller
{
    /**
     * Метод создания объекта игрового поля.
     *
     * @param AddAnimalRequest $request Данные запроса.
     * @param int              $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createObject(AddAnimalRequest $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        GameFieldObjectService::createObject($data);
    }

    /**
     * Метод создания объектов игрового поля с рандомными координатами.
     *
     * @param AddAnimalsWithRandomCoordinatesRequest $request Данные запроса.
     * @param int                                    $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createObjectsWithRandomCoordinates(AddAnimalsWithRandomCoordinatesRequest $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        GameFieldObjectService::createObjectsWithRandomCoordinates($data);
    }

    /**
     * Метод получения списка всех объектов игрового поля.
     *
     * @param GetAnimalsRequest $request Данные запроса.
     * @param int               $fieldId Игровое поле.
     *
     * @return Collection
     */
    public function getObjectList(GetAnimalsRequest $request, int $fieldId) : Collection
    {
        return GameFieldObjectService::getObjectList($fieldId);
    }
}

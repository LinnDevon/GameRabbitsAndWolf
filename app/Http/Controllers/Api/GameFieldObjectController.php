<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createObject(Request $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        GameFieldObjectService::createObject($data);
    }

    /**
     * Метод создания объектов игрового поля с рандомными координатами.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function createObjectsWithRandomCoordinates(Request $request, int $fieldId)
    {
        $data                  = $request->all();
        $data['game_field_id'] = $fieldId;

        GameFieldObjectService::createObjectsWithRandomCoordinates($data);
    }

    /**
     * Метод получения списка всех объектов игрового поля.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     *
     * @return Collection
     */
    public function getObjectList(Request $request, int $fieldId) : Collection
    {
        return GameFieldObjectService::getObjectList($fieldId);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GameFieldObjectService;
use App\Services\GameFieldService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Класс контроллера игрового поля.
 */
class GameFieldController extends Controller
{
    /**
     * Метод создания новой игры.
     *
     * @param Request $request Данные запроса.
     */
    public function createGameField(Request $request)
    {
        GameFieldService::createGameField($request->all());
    }

    /**
     * Метод выполнения следующего шага на игровом поле.
     *
     * @param Request $request Данные запроса.
     * @param int     $fieldId Игровое поле.
     */
    public function executeNextStep(Request $request, int $fieldId)
    {
        GameFieldService::executeNextStep($fieldId);
    }
}

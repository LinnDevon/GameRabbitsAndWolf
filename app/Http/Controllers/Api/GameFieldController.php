<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGameRequest;
use App\Http\Requests\NextStepRequest;
use App\Services\GameFieldObjectService;
use App\Services\GameFieldService;
use Exception;
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
     * @param CreateGameRequest $request Данные запроса.
     */
    public function createGameField(CreateGameRequest $request)
    {
        GameFieldService::createGameField($request->all());
    }

    /**
     * Метод выполнения следующего шага на игровом поле.
     *
     * @param NextStepRequest $request Данные запроса.
     * @param int             $fieldId Игровое поле.
     *
     * @throws Exception
     */
    public function executeNextStep(NextStepRequest $request, int $fieldId)
    {
        GameFieldService::executeNextStep($fieldId);
    }
}

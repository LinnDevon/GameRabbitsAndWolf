<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\GameFieldController;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Начать новую игру
//  curl -X 'POST' -d height=3 -d width=31 -d count_steps=3 http://127.0.0.1:8000/api/start_game
Route::post('start_game', [GameFieldController::class, 'createGameField']);

// Добавить животное
// curl -X 'POST' -d x=3 -d y=31 -d type="Волк" http://127.0.0.1:8000/api/field/1/add_animal - для создания одного
Route::post('field/{field_id}/add_animal', [AnimalController::class, 'createAnimal'])->where('field_id', '[0-9]+');

// Добавить несколько животных с рандомными координатами
// curl -X 'POST' -d count=3 -d type="Волк" http://127.0.0.1:8000/api/field/1/add_animals_with_random_coordinates
Route::post(
    'field/{field_id}/add_animals_with_random_coordinates',
    [AnimalController::class, 'createAnimalsWithRandomCoordinates']
)->where('field_id', '[0-9]+');

// Получить список животных с координатами
// curl -X 'GET' http://127.0.0.1:8000/api/field/1/get_animals
Route::get('field/{field_id}/get_animals', [AnimalController::class, 'getAnimalList'])->where('field_id', '[0-9]+');

// Выполнить следующий ход на игровом поле
// curl -X 'PATCH' http://127.0.0.1:8000/api/field/1/next_step
Route::patch('field/{field_id}/next_step', [GameFieldController::class, 'executeNextStep'])->where('field_id', '[0-9]+');

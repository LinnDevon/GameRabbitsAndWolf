<?php

namespace App\Http\Requests;

use App\Models\GameField;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс реквеста с валидацией для получения списка объектов на игровом поле.
 */
class GetAnimalsRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var GameField $field */
        $field = GameField::find($this->route()->parameter('field_id'));

        return isset($field);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [];
    }
}

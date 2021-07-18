<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс реквеста с валидацией для создания новой игры.
 */
class CreateGameRequest extends FormRequest
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'height'      => 'required|numeric|min:1|max:10000',
            'width'       => 'required|numeric|min:1|max:10000',
            'count_steps' => 'required|numeric|min:1|max:10000',
        ];
    }
}

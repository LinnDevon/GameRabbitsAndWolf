<?php

namespace App\Http\Requests;

use App\Models\GameField;
use App\Models\ObjectType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс реквеста с валидацией для создания нового объекта на поле.
 */
class AddAnimalRequest extends FormRequest
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
        /** @var GameField $field */
        $field = GameField::find($this->route()->parameter('field_id'));
        return [
            'x'    => [
                'required',
                'numeric',
                'min:0',
                'max:10000',
                function ($attribute, $value, $fail) use ($field) {
                    if ($field->width < $value) {
                        $fail('The ' . $attribute . ' must not be greater than ' . $field->width . ' characters.');
                    }
                },
            ],
            'y'    => [
                'required',
                'numeric',
                'min:0',
                'max:10000',
                function ($attribute, $value, $fail) use ($field) {
                    if ($field->height < $value) {
                        $fail('The ' . $attribute . ' must not be greater than ' . $field->height . ' characters.');
                    }
                },
            ],
            'type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ( !in_array($value, ObjectType::all()->pluck('name')->toArray())) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                },
            ],
        ];
    }
}

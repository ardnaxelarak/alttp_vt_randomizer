<?php

namespace ALttP\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ALttP\Rules\MysteryWeightsRule;

class CreateMysteryGame extends FormRequest
{
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
     * @todo have these return better error strings
     *
     * @return array
     */
    public function rules()
    {
        return [
            'weights' => [
                new MysteryWeightsRule
            ]
        ];
    }
}


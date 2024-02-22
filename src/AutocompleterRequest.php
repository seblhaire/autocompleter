<?php

namespace Seblhaire\Autocompleter;

use Illuminate\Foundation\Http\FormRequest;

class AutocompleterRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'search' => 'required|string',
            'maxresults' => 'required|numeric|min:3'
        ];
    }
}

<?php

namespace Modules\Admin\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class SaveMenuItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('type')) {
            $rules = [];

            switch ($this->get('type')) {
                case 'route':
                    $rules = [
                        'translations.*.name'  => 'required',
                        'translations.*.value' => 'required',
                    ];

                    //TODO currently works, but if possible should think of something more efficient
                    foreach ($this->get('translations', []) as $key => $translation) {
                        foreach (Route::getRoutes() as $route) {
                            if ($route->getName() == $translation['value']) {
                                foreach ($route->parameterNames() as $param) {
                                    $rules['translations.' . $key . '.parameters.' . $param] = 'required';
                                }
                            }
                        }
                    }

                    break;
                case 'url':
                    $rules = [
                        'translations.*.value' => 'required',
                    ];
                    break;
                case 'page':
                    $rules = [
                        'translations.*.value' => 'required',
                    ];
                    break;
            }

            return $rules;
        } else {
            return [
                'type' => 'required'
            ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

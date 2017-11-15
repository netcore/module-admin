<?php

namespace Modules\Admin\Http\Requests;

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
                        'name'  => 'required',
                        'value' => 'required',
                    ];

                    //TODO currently works, but if possible should think of something more efficient
                    if ($this->has('value')) {
                        foreach (Route::getRoutes() as $route) {
                            if ($route->getName() == $this->get('value')) {
                                foreach ($route->parameterNames() as $param) {
                                    $rules['parameters.' . $param] = 'required';
                                }
                            }
                        }
                    }

                    break;
                case 'url':
                    $rules = [
                        'value' => 'required',
                    ];
                    break;
                case 'page':
                    $rules = [
                        'value' => 'required',
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

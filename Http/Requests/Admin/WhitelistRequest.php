<?php

namespace Modules\Admin\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WhitelistRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $whitelist = $this->route('whitelist');

        $rules = [
            'ip'   => 'required|unique:netcore_admin__ip_whitelist,ip' . ($whitelist ? ',' . $whitelist->id : ''),
            'type' => 'required|in:exact,wildcard'
        ];

        if ($this->get('type') === 'exact') {
            $rules['ip'] .= '|ip';
        }

        return $rules;
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}

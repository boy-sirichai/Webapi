<?php

namespace App\Http\Requests\{replace};

use App\Classes\core_validation;

class {replace}Request extends core_validation
{
    /**
     * @var string[]
     */
    protected $urlParameters = [];

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return array (
           #'name.required' => ':attribute is required'
        );

    }

    public function attributes()
    {
        return array(
            #'name' => trans('unit.name'),
        );
    }
}

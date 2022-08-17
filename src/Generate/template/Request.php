<?php

namespace App\Http\Requests\{replace};

use App\Classes\core_validation;

class {replace}Request extends core_validation
{
    /**
     * @var string[]
     */
    protected $urlParameters = [
        'id',
    ];

    /**
     * @return string[]
     */
    public function rules()
    {
        return array();
    }

    /**
     * @return array
     */
    public function messages()
    {
        return array();
    }
}

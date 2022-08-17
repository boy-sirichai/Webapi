<?php

namespace App\Http\Controllers\{replace};

use App\Http\Requests\{replace}\{replace}Request;
use App\Services\{replace}\{replace}Service;
use App\Http\Controllers\ApiController;

class {replace}Controller extends ApiController
{
    private ${replace_sm}Services;

    /**
     * Constructor
     *
     * @param {replace}Service ${replace_sm}Service
     * @param {replace}Transformer ${replace_sm}Transformer
     */
    public function __construct({replace}Service ${replace_sm}Service)
    {
        $this->{replace_sm}Service  = ${replace_sm}Service;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function {replace}Functional({replace}Request $request)
    {
        ${replace_sm}s = $this->{replace_sm}Service->lists($request->all());

        return response()->json([
            'error' => 0,
            'result' => ${replace_sm}s ?? []
        ]);
    }
}

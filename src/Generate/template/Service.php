<?php

namespace App\Services\{replace};

use App\Library\Logging\LoggingHelperTraits;
use App\Services\DataProvider\DataProvider;

class {replace}Service extends DataProvider
{
    use LoggingHelperTraits;

    /**
     * @var string
     */
    protected $endpoint = '/api/**';

    /**
     * @var string
     */
    protected $env = '';
}

<?php

return array(
    'template' => array(
        'Request'            => array(
            'resource' => 'template/Request.php',
            'target'   => 'app/Http/Requests/',
            'needDir'  => true,
        ),
        'Controller'         => array(
            'resource'  => 'template/Controller.php',
            'target'    => 'app/Http/Controllers/',
            'namespace' => 'App/',
            'needDir'   => false,
        ),
        'Service'              => array(
            'resource' => 'template/Service.php',
            'target'   => 'app/Services/',
            'needDir'  => false,
        ),
        'Route'              => array(
            'resource' => 'template/Route.php',
            'target'   => 'Routes/',
            'needDir'  => true,
        ),
    ),
    'using_repository' => false,
);
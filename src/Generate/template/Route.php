<?php

$router->group(
    array(
        'prefix' => 'v1/{replace_sm}s',
        'middleware' => array('login'),
        'namespace' => '{replace}',
    ),
    function () use ($router) {
        $router->get('/', array('uses' => '{replace}Controller@{replace}Functional'));
    }
);

<?php
use Cake\Routing\Router;

Router::plugin(
    'SemanticUI',
    ['path' => '/semantic-u-i'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);

<?php

return array(
    'general' => array(
        'router' => 'Fruit\PuxRouter'
    ),

    'pux' => array(
        'mux' => implode(DIRECTORY_SEPARATOR, ['route', 'compiled.php'])
    ),

    'twig' => array(
        'template_dir' => BASE_DIR . '/templates',
        'debug'        => true,
        'cache_dir'    => '/tmp'
    ),
    
    'db' => array(
        'default' => array(
            'constr' => 'mysql:host=127.0.0.1;dbname=buytogether;charset=utf8',
            'user'   => 'buytogether',
            'pass'   => '1234'
        ),
    ),

    'upload_group' => array(
        'dir' => BASE_DIR . '/_group',
        'prefix' => 'ul_'
    ),

    'upload_user' => array(
        'dir' => BASE_DIR . '/_member',
        'prefix' => 'ul_'
    )
);

<?php
define('BASE_DIR', dirname(__DIR__)); // set upper directory as base dir
require implode(DIRECTORY_SEPARATOR, [
    BASE_DIR,
    'vendor',
    'autoload.php'
]);

$mux = new Pux\Mux();

/**
 * Buy
 */
$mux->get('/', ['BuyTogether\Controller\BuyController','index']);
$mux->get('/buy/view/:b', ['BuyTogether\Controller\BuyController','view'],
    ['require' => ['b' => '\d+']]
);
$mux->any('/buy/start', ['BuyTogether\Controller\BuyController','start']);
$mux->get(
    '/images/:img_token',
    ['BuyTogether\Controller\BuyController','showImg']
);
$mux->get('/buy/join/:b',['BuyTogether\Controller\BuyController','join'],
	['require' =>['b' => '\d+']]
);
/**
 * User
 */
$mux->get('/user/getall',['BuyTogether\Controller\UserController','getAllUser']);
$mux->get('/user/userlist',['BuyTogether\Controller\UserController','userList']);
$mux->any('/user/register', ['BuyTogether\Controller\UserController','addUser']);
$mux->any('/user/view',['BuyTogether\Controller\UserController','view']);
$mux->any('/user/login',['BuyTogether\Controller\UserController','login']);
$mux->get('/user/logout',['BuyTogether\Controller\UserController','logout']);


return $mux;

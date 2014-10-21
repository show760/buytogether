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
$mux->get('/images/:token',['BuyTogether\Controller\BuyController','showImg']);
/**
 * Join
 */
$mux->any('/buy/join/:b',['BuyTogether\Controller\JoinController','join'],
	['require' =>['b' => '\d+']]
);
$mux->get('/join/delete/:bid/:jid',['BuyTogether\Controller\JoinController','groupDelete'],
	['require' =>['bid' => '\d+', 'jid' => '\d+']]
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
$mux->get('/userimages/:token',['BuyTogether\Controller\UserController','showUserImg']);
/* Group */
$mux->any('/user/mylist',['BuyTogether\Controller\GroupController','myList']);
$mux->any('/user/mygroup/:bid',['BuyTogether\Controller\GroupController','myGroup'],
	['require' =>['bid' => '\d+']]
);
$mux->any('/user/mygroup/all/:bid',['BuyTogether\Controller\GroupController','allOrdersChange'],
	['require' =>['bid' => '\d+']]
);
$mux->any('/user/myjoin/:jid',['BuyTogether\Controller\GroupController','myJoin'],
	['require' =>['jid' => '\d+']]
);
$mux->any('/user/uploadjoinimg/:jid',['BuyTogether\Controller\GroupController','uploadJoinImg'],
	['require' =>['jid' => '\d+']]
);
$mux->get('/joinimages/:token',['BuyTogether\Controller\GroupController','showJoinImg']);
$mux->any('/user/groupstatus/:bid',['BuyTogether\Controller\GroupController','groupStatus'],
	['require' =>['bid' => '\d+']]
);

return $mux;

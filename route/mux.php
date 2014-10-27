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
$mux->any('/user/groupstatus/:bid',['BuyTogether\Controller\GroupController','groupStatus'],
	['require' =>['bid' => '\d+']]
);
/*ImgPlus*/
$mux->get('/images/:token',['BuyTogether\Library\ImgLibrary','showImgByBid']);
$mux->get('/userimages/:token',['BuyTogether\Library\ImgLibrary','showImgByUid']);
$mux->get('/joinimages/:token',['BuyTogether\Library\ImgLibrary','showImgByJid']);

/*Post*/
$mux->get('/thread/:bid',['BuyTogether\Controller\PostController','showThread'],
	['require' =>['bid' => '\d+']]
);
$mux->get('/thread/deletepost/:pid',['BuyTogether\Controller\PostController','deletePost'],
	['require' =>['pid' => '\d+']]
);
$mux->post('/thread/addpost',['BuyTogether\Controller\PostController','addPost']);
$mux->any('/thread/editpost/:pid',['BuyTogether\Controller\PostController','editPost'],
	['require' =>['pid' => '\d+']]
);
return $mux;

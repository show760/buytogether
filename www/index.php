<?php
require(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']));
define('BASE_DIR', dirname(__DIR__));
$cfg = new Fruit\Config(dirname(__DIR__));
Fruit\Seed::fertilize($cfg);
$cfg->getRouter()->route($_SERVER['DOCUMENT_URI']);

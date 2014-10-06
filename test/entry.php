<?php
require('init.php');
require(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']));
BuyTogether\Model\Buy::fertilize(BuyTogetherTest\Tool::getConfig());

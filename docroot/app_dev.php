<?php

ini_set('display_errors', 1);
error_reporting(-1);

$env = 'prod';

$app = require __DIR__.'/front.php';

$app->run();

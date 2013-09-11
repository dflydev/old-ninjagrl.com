<?php

ini_set('display_errors', 0);

$env = 'prod';

$app = require __DIR__.'/front.php';

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/../var/cache/',
));

$app['http_cache']->run();

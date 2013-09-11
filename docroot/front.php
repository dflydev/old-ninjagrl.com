<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

if (!isset($env)) {
    http_response_code(503);
    echo 'Front controller must have environment configured.';
    exit;
}

require __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Igorw\Silex\ConfigServiceProvider(
    __DIR__.'/../config/config.json'
));

$app->register(new Igorw\Silex\ConfigServiceProvider(
    __DIR__."/../config/$env.json"
));

Ninjagrl\Web\Silex\AppConfigurer::configureApplication($app);
Ninjagrl\Web\Silex\WebappConfigurer::configureApplication($app);
Ninjagrl\Web\Silex\ControllersConfigurer::configureApplication($app);

return $app;

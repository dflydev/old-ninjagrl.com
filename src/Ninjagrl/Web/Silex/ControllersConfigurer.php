<?php

namespace Ninjagrl\Web\Silex;

use Silex\Application;

class ControllersConfigurer
{
    public static function configureApplication(Application $app)
    {
        $app->get('/', function () {
            return 'Hello World';
        });
    }
}

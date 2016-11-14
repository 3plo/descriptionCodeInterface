<?php

/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 10.11.2016
 * Time: 15:47
 */
class Autoloader
{
    public function loadClass($class)
    {
        $rootDir = __DIR__ . '/../';
        $path = $rootDir . $class . '.php';
        if (!class_exists($class))
        {
            if (file_exists($path))
            {
                require($path);
            }
        }
    }
}

spl_autoload_register([new Autoloader(), 'loadClass']);
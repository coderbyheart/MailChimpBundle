<?php

/**
 * @author    Markus Tacker <m@cto.hiv>
 */

use Doctrine\Common\Annotations\AnnotationRegistry;

if (!is_file($loaderFile = __DIR__ . '/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

$loader = require $loaderFile;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

require_once 'autoload.php';

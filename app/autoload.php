<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include __DIR__.'/../vendor/autoload.php';

$loader->add('Doctrine\ODM\MongoDB\Tests', __DIR__ . '/../vendor/doctrine/mongodb-odm/tests');

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
AnnotationDriver::registerAnnotationClasses();

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
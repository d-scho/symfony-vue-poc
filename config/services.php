<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->load('App\\', '../src/')
    ;
};
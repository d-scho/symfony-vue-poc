<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return function (FrameworkConfig $framework) {
    $framework->cache()
        ->pool('vue_app_assets_cache')
        ->adapters('cache.adapter.filesystem');
};
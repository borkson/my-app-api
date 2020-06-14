<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;

class XClacksOverheadMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : XClacksOverheadMiddleware
    {
        return new XClacksOverheadMiddleware();
    }
}

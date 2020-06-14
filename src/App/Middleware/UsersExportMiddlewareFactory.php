<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class UsersExportMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : UsersExportMiddleware
    {
        return new UsersExportMiddleware();
    }
}

<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class UsersExportMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : UsersExportMiddleware
    {
        return new UsersExportMiddleware($container->get(AdapterInterface::class));
    }
}

<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

class UsersImportMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : UsersImportMiddleware
    {
        return new UsersImportMiddleware($container->get(AdapterInterface::class));
    }
}

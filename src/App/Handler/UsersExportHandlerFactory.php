<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class UsersExportHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UsersExportHandler
    {
        return new UsersExportHandler();
    }
}

<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class UsersImportHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UsersImportHandler
    {
        return new UsersImportHandler();
    }
}

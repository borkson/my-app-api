<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Db\Adapter\Adapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UsersExportMiddleware implements MiddlewareInterface
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $query = $this->dbAdapter->query('SELECT id, name, username, website, address_id FROM `user`');
        $users = $query->execute();

        $usersFullData = [];
        foreach ($users as $user) {
            $query = $this->dbAdapter->query('SELECT city FROM `user_address` WHERE address_id = :address_id');
            $userAddress = $query->execute([
                'address_id' => $user['address_id'],
            ])->current();

            // odzwierciedlenie struktury danych wyświetlanych na froncie
            $userToAdd = [
                'id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'website' => $user['website'],
                'address' => [
                    'city' => $userAddress['city']
                ]
            ];
            $usersFullData[] = $userToAdd; // skrótowy zapis dla array_push();
        }

        $request = $request->withAttribute('users', $usersFullData);
        return $handler->handle($request);

        // $users = (array)$query->execute();
        // obiekt typu result -> rzutowanie na tablicę
    }
}

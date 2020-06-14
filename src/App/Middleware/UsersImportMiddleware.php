<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Request;
use Laminas\Http\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UsersImportMiddleware implements MiddlewareInterface
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $apiData = new Request();
        $apiData->setUri('https://jsonplaceholder.typicode.com/users');
        $client = new Client();
        $response = $client->send($apiData);
        $getBody = json_decode($response->getBody(), true);

//        function saveUsers($getBody) {};

        foreach ($getBody as $user) {
            $query = $this->dbAdapter->query("insert into user_company (name, catchPhrase, bs) VALUES (:name, :catchPhrase, :bs)");
            $query->execute([
                'name' => $user['company']['name'],
                'catchPhrase' => $user['company']['catchPhrase'],
                'bs' => $user['company']['bs']]);
            $companyId = $this->dbAdapter->driver->getLastGeneratedValue();

            $query = $this->dbAdapter->query("insert into address_geo (lat, lng) 
                                                VALUES (:lat, :lng)");
            $query->execute([
                'lat' => $user['address']['geo']['lat'],
                'lng' => $user['address']['geo']['lng']]);
            $geoId = $this->dbAdapter->driver->getLastGeneratedValue();

            $query = $this->dbAdapter->query("insert into user_address (street, suite, city, zipcode, geo_id) 
                                                VALUES (:street, :suite, :city, :zipcode, :geo_id)");
            $query->execute([
                'street' => $user['address']['street'],
                'suite' => $user['address']['suite'],
                'city' => $user['address']['city'],
                'zipcode' => $user['address']['zipcode'],
                'geo_id' => $geoId]);
            $addressId = $this->dbAdapter->driver->getLastGeneratedValue();

            $query = $this->dbAdapter->query("insert into user (username, email, phone, website, address_id, company_id) 
                                                VALUES (:username, :email, :phone, :website, :address_id, :company_id)");
            $query->execute([
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'website' => $user['website'],
                'address_id' => $addressId,
                'company_id' => $companyId]);
            // wartości do execute jako zabezpieczenie przed sql injection;
        }

        // druga akcja - wyciąga uzykonikow z mojej bazy - select;
        $request = $request->withAttribute('users', $getBody);

        return $handler->handle($request);
    }
}

<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class XClacksOverheadMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
         $response = $handler->handle($request);
         return $response->withHeader('Access-Control-Allow-Origin', '*');

         //* wild card - pozwala na podstawienie dowolnej wartosci, zezwla na połaczenie do api dla danych wartosci (wartosc - adres), na produkcji nie dawac wild cardsów
    }
}

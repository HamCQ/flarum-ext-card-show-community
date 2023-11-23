<?php
namespace HamCQ\CardShow\Controllers;

use Flarum\Http\RequestUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Flarum\Foundation\ValidationException;
use HamCQ\CardShow\Logic\ViewAddLogic;

class ViewAddController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        // $actor->assertRegistered();
        $ip = $request->getAttribute('ipAddress');
        return new JsonResponse( resolve(ViewAddLogic::class)->save($actor, $request->getParsedBody(), $ip) );
    }
}
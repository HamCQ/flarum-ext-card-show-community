<?php
namespace HamCQ\CardShow\Controllers;

use Flarum\Http\RequestUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Flarum\Foundation\ValidationException;
use HamCQ\CardShow\Logic\HideLogic;

class HideController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertRegistered();
        if(!$actor->phone){
            throw new ValidationException(['msg' => "请先完成手机号认证"]);
        }

        return new JsonResponse( resolve(HideLogic::class)->save($actor, $request->getParsedBody()) );
    }
}
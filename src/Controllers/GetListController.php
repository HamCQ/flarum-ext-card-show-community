<?php

namespace HamCQ\CardShow\Controllers;

use Flarum\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Flarum\Http\UrlGenerator;
use Flarum\Query\QueryCriteria;
use Flarum\Http\RequestUtil;
use HamCQ\CardShow\Filter\GetListFilter;
use HamCQ\CardShow\Serializer\GetListSerializer;

class GetListController extends AbstractListController
{
    public $serializer = GetListSerializer::class;

    public $limit = 12;

    public $maxLimit = 20;

    protected $filterer;
    public $include = ["user"];//关联操作

    public $sort = ['created_time' => 'desc'];

    public $sortFields = ['created_time', 'like_count', 'view_count'];

    // protected $searcher;

    protected $url;

    public function __construct(GetListFilter $filterer, UrlGenerator $url)
    {
        $this->filterer = $filterer;
        $this->url = $url;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);

        $filters = $this->extractFilter($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);
        $sortIsDefault = $this->sortIsDefault($request);

        $criteria = new QueryCriteria($actor, $filters, $sort, $sortIsDefault);

        $results = $this->filterer->filter($criteria, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->to('api')->route('hamcqCardShow.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $results->areMoreResults() ? null : 0
        );

        $results = $results->getResults();

        $this->loadRelations($results, $include);

        return $results;
    }
}
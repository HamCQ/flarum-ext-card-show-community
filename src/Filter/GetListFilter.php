<?php

namespace HamCQ\CardShow\Filter;

use Flarum\Filter\AbstractFilterer;
use Flarum\User\User;
use HamCQ\CardShow\Model\QslCardShow;
use Illuminate\Database\Eloquent\Builder;

class GetListFilter extends AbstractFilterer
{
    /**
     * @param array $filters
     * @param array $filterMutators
     */
    public function __construct(array $filters, array $filterMutators)
    {
        parent::__construct($filters, $filterMutators);
    }

    protected function getQuery(User $actor): Builder
    {
        return QslCardShow::query()->where([
            "status" => 1
        ]);
    }
}

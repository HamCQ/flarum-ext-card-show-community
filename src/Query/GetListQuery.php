<?php

namespace  HamCQ\CardShow\Query;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;

class GetListQuery implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'id';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $filterState->getQuery()
            ->where([
               [ "status", "=" , 1]
            ]);
    }
}
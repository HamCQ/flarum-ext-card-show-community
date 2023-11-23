<?php

/*
 * This file is part of hamcq/qsl-card-show.
 *
 * Copyright (c) 2023 Emin.lin.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace HamCQ\CardShow;

use Flarum\Extend;
use HamCQ\CardShow\Filter\GetListFilter;
use HamCQ\CardShow\Model\QslCardShow;
use HamCQ\CardShow\Notification\LikedNotification;
use HamCQ\CardShow\Query\GetListQuery;
use HamCQ\CardShow\Serializer\GetListSerializer;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
        ->route('/qslCardShow', 'qslCardShow', Controllers\IndexController::class),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Filter(GetListFilter::class))
        ->addFilter(GetListQuery::class),

    //数据关联用户
    (new Extend\Model(User::class))
        ->relationship('cardShowList', function ($user) {
            return $user->hasOne(QslCardShow::class, 'uid');
    }),

    (new Extend\Routes('api'))
        ->get('/card_show_list', 'hamcqCardShow.list', Controllers\GetListController::class)
        ->post('/hamcq/qsl_card_show/add', 'hamcqQslCardShow.create', Controllers\AddController::class)
        ->post('/hamcq/qsl_card_show/hide', 'hamcqQslCardShow.hide', Controllers\HideController::class)
        ->post('/hamcq/qsl_card_show/like', 'hamcqQslCardShow.like', Controllers\LikeController::class)
        ->post('/hamcq/qsl_card_show/view', 'hamcqQslCardShow.view', Controllers\ViewAddController::class),
    
    (new Extend\Notification())
        ->type(LikedNotification::class, GetListSerializer::class, ['alert']),
];

<?php

namespace  HamCQ\CardShow\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;


class QslCardShow extends AbstractModel
{
    protected $table = 'qsl_card_show';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
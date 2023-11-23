<?php

namespace  HamCQ\CardShow\Notification;

use Flarum\Notification\Blueprint\BlueprintInterface;
use HamCQ\CardShow\Model\QslCardShow;

class LikedNotification implements BlueprintInterface
{
    public $show;

    public $actor;

    public function __construct($actor, QslCardShow $show)
    {
        $this->actor=$actor;
        $this->show=$show;
    }

    public function getFromUser()
    {
        return $this->actor;
    }

    public function getSubject()
    {
        return $this->show;
    }

    public function getData()
    {
        return null;
    }

    public static function getType()
    {
        return 'cardShowLiked';
    }

    public static function getSubjectModel()
    {
        return QslCardShow::class;
    }
}

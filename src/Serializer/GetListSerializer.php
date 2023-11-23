<?php

namespace HamCQ\CardShow\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use HamCQ\CardShow\Model\QslCardAction;

class GetListSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'cardShowList';

    protected $actor;

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($qslCardShow)
    {
        // $user = User::select('username')->where("id", $qslCardShow->user_id)->first();
        $status = QslCardAction::where([
            "card_show_id" =>$qslCardShow->id,
            "user_id" => $this->actor->id,
            "type" => 0
        ])->first();
        return [
            'id' => $qslCardShow->id,
            'uid' => $qslCardShow->user_id,
            'img_list' => explode(",",$qslCardShow->img_list),
            'cover_width' => $qslCardShow->cover_width,
            'cover_height' => $qslCardShow->cover_height,
            'created_time' => $qslCardShow->created_time,
            'like_count' => $qslCardShow->like_count,
            'view_count' => $qslCardShow->view_count,
            'exchange_count' => $qslCardShow->exchange_count,
            'is_my_like' => $status ? true : false,
            'status' => $qslCardShow->status == 1 ? true : false,
        ];
    }

    /**
     * @param $username_request
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    protected function user($user)
    {
        return $this->hasOne($user, UserSerializer::class);
    }
}
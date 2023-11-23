<?php
namespace HamCQ\CardShow\Logic;
use Flarum\Foundation\ValidationException;
use HamCQ\CardShow\Model\QslCardAction;
use HamCQ\CardShow\Model\QslCardShow;
use Flarum\Notification\NotificationSyncer;
use HamCQ\CardShow\Notification\LikedNotification;
use Flarum\User\User;

class LikeLogic
{
    public function save($actor,$data)
    {
        $msg = ["status" => false , "msg" => ""];
        $showId = isset($data["show_id"]) ? $data["show_id"] : 0;
        if(!$showId){
            throw new ValidationException(['msg' => "您选择的内容有误"]);
        }
        $cardStatus = QslCardShow::where([
            "id" => $showId
        ])->first();
        if(!$cardStatus){
            throw new ValidationException(['msg' => "您选择的内容有误"]);
        }
        if($cardStatus->user_id==$actor->id){
            throw new ValidationException(['msg' => "您不能点赞自己的内容"]);
        }

        $status = QslCardAction::where([
            "card_show_id" => $showId,
            "user_id" => $actor->id,
            "type" => 0
        ])->first();

        if($status){
            throw new ValidationException(['msg' => "您已点赞过此内容"]);
        }

        QslCardAction::insert([
            "card_show_id" => $showId,
            "user_id" => $actor -> id,
            "type" => 0,
            "created_time" => time()
        ]);

        QslCardShow::where([
            "id" => $showId,
        ])->increment("like_count");

        $notification = resolve(NotificationSyncer::class);
        $user = User::findOrFail($cardStatus->user_id);

        $notification->sync(new LikedNotification($actor,$cardStatus),[$user]);

        $msg["status"] = true;
        return $msg;
    }
}
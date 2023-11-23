<?php
namespace HamCQ\CardShow\Logic;

use HamCQ\CardShow\Model\QslCardShow;
use HamCQ\CardShow\Model\QslCardViews;

class ViewAddLogic
{
    public function save($actor,$data,$ip)
    {
        $msg = ["status" => false , "msg" => ""];
        $showId = isset($data["show_id"]) ? $data["show_id"] : 0;
        if(!$ip || !$showId){
            return $msg;
        }

        $uid = 0;
        if($actor->id){
            $uid = $actor->id;
        }
        $status = QslCardViews::where([
            "card_show_id" => $showId,
            "ip" => $ip
        ])->first();
        if($status){
            return $msg;
        }

        QslCardViews::insert([
            "user_id" => $uid,
            "card_show_id" => $showId,
            "ip" => $ip,
            "created_time" => time()
        ]);

        QslCardShow::where([
            "id" => $showId,
        ])->increment("view_count");
        $msg["status"] = true;
        return $msg;
    }
}
<?php
namespace HamCQ\CardShow\Logic;
use Flarum\Foundation\ValidationException;
use HamCQ\CardShow\Model\QslCardShow;

class HideLogic
{
    public function save($actor,$data)
    {
        $msg = ["status" => false , "msg" => ""];
        $showId = isset($data["show_id"]) ? $data["show_id"] : 0;
        if(!$showId){
            return $msg;
        }
        $cardStatus = QslCardShow::where([
            "id" => $showId
        ])->first();
        if(!$cardStatus){
            throw new ValidationException(['msg' => "您选择的内容有误"]);
        }
        if($cardStatus->user_id!=$actor->id){
            throw new ValidationException(['msg' => "您只能删除自己的内容"]);
        }
        QslCardShow::where([
            "id" => $showId,
        ])->update([
            "status" => 0,
            "update_time" => time()
        ]);
        $msg["status"] = true;
        return $msg;
    }
}
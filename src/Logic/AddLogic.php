<?php
namespace HamCQ\CardShow\Logic;
use Flarum\Foundation\ValidationException;
use HamCQ\CardShow\Model\FofUpload;
use HamCQ\CardShow\Model\QslCardShow;

class AddLogic
{
    public function save($actor,$data)
    {
        $msg = ["status" => false , "msg" => ""];
        $uuidList = isset($data["uuidList"]) ? $data["uuidList"] : [];
        $coverWidth = isset($data["width"]) ? $data["width"] : 0;
        $coverHeight = isset($data["height"]) ? $data["height"] : 0;
        if(!$uuidList){
            throw new ValidationException(['msg' => "请选择图片"]);
        }
        if(count($uuidList)>2||count($uuidList)==0){
            throw new ValidationException(['msg' => "图片数量有误"]);
        }
        $filePath = [];
        
        foreach($uuidList as $k => $img){
            $imgStatus = FofUpload::select("id","url")
                ->where([
                    "actor_id" => $actor->id,
                    "id" => $img,
                    "hide_from_media_manager" => 0
                ])->first();
            if(!$imgStatus){
                throw new ValidationException(['msg' => "您选择的图片有误"]);
            }
            array_push($filePath,$imgStatus->url);
        }
        $fileString = implode(',',$filePath);
        $status = QslCardShow::where([
            "user_id" => $actor->id,
            "img_list" => $fileString,
            "status" => 1
        ])->first();
        if($status){
            throw new ValidationException(['msg' => "您已分享过此内容"]);
        }
        QslCardShow::insert([
            "user_id" => $actor->id,
            "img_list" => $fileString,
            "cover_width" => (int)$coverWidth,
            "cover_height" => (int)$coverHeight,
            "status" => 1,
            "created_time" => time()
        ]);
        $msg["status"] = true;
        return $msg;
    }
}
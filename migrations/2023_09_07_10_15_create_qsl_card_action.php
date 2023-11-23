<?php

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Builder;


return [
    'up' => function (Builder $schema) {
        if ($schema->hasTable('qsl_card_action')) {
            return;
        }
       
        $schema->create('qsl_card_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_show_id')->index();
            $table->unsignedInteger('user_id');
            $table->tinyInteger('type')->default(0); //0点赞
            $table->integer('created_time');
        });
    },
    'down' => function (Builder $schema) {
        
    },
];
<?php

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Schema\Builder;


return [
    'up' => function (Builder $schema) {
        if ($schema->hasTable('qsl_card_views')) {
            return;
        }
       
        $schema->create('qsl_card_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('card_show_id')->index();
            $table->string('ip', 32);
            $table->integer('created_time')->default(0);
        });
    },
    'down' => function (Builder $schema) {
        
    },
];
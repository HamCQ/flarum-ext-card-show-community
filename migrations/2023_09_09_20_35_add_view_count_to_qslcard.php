<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (!$schema->hasColumn('qsl_card_show', 'view_count')) {
            $schema->table('qsl_card_show', function (Blueprint $table) use ($schema) {
                $table->integer('view_count')->default(0);
            });
        }
    },
    'down' => function (Builder $schema) {
        
    },
];
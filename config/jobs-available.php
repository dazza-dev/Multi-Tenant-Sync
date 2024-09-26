<?php

use App\Rules\DBQuery;

return [

    /*
    |--------------------------------------------------------------------------
    | Jobs Available
    |--------------------------------------------------------------------------
    */

    'query' => [
        'title' => 'DB Query',
        'description' => 'Executes a database query.',
        'file' => \App\Jobs\Query::class,
        'allow_params' => true,
        'validations' => ['required', 'string', new DBQuery],
    ],

    'other' => [
        'title' => 'Other',
        'description' => 'Executes other jobs.',
        'file' => \App\Jobs\Other::class,
        'allow_params' => false,
    ],

];

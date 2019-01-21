<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repositorio extends Model
{
    protected $primaryKey = 'REP_ID';

    protected $fillable = [
        'REP_LANG', 'REP_ORDER', 'REP_NAME', 'REP_AUTHOR', 'REP_URL', 'REP_DESC', 'REP_STARS', 'REP_FORKS', 'REP_BUILTBY', 'REP_TREE', 'REP_SHA'
    ];

    protected $attributes = [
        'REP_TREE' => ''
    ];

    protected  $dates = [
        'created_at', 'updated_at'
    ];
}
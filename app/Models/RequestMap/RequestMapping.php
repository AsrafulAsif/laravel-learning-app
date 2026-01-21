<?php

namespace App\Models\RequestMap;

use Illuminate\Database\Eloquent\Model;

class RequestMapping extends Model
{
    protected $connection = 'mysql';
    protected $table = 'request_mapping';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'request_id',
        'request_json_template',
    ];
}

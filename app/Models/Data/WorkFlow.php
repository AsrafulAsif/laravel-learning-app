<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class WorkFlow extends Model
{

    protected $connection = 'mysql';

    protected $table = 'work_flows';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'workflow_name_id',
        'current_role',
        'next_role',
    ];
}

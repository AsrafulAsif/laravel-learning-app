<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class WorkFlowName extends Model
{
    protected $connection = 'mysql';

    protected $table = 'workflow_names';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    protected $casts = [
        'created_by' => 'integer',
    ];

    public function steps()
    {
        return $this->hasMany(WorkFlow::class, 'workflow_name_id');
    }
}

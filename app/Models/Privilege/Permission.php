<?php

namespace App\Models\Privilege;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $connection = 'mysql';
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'permission_name',
        'permission_display_name',
        'permission_description',
        'controller_name',
        'api_url',
        'method_name',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];
}

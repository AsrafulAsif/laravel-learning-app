<?php

namespace App\Models\Privilege;

use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{

    protected $connection = 'mysql';

    protected $table = 'role_permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'permission_id',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
            'deleted_at' => 'timestamp',
        ];
    }
}

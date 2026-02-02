<?php

namespace App\Models\Privilege;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $connection = 'mysql';

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'role_name',
        'role_display_name',
        'role_description',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    //A Role can have many Permissions → also many-to-many.
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )->wherePivot('is_deleted', false)
            ->where('permissions.is_deleted', false);
    }

}

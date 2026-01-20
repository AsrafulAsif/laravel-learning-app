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

    /*
     * MySQL stores booleans as TINYINT(1) (0 or 1), so when for retrieve data,
     * we get integers instead of actual boolean values.
    */
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


    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->withTimestamps();
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')
            ->withTimestamps();
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()
            ->where('permission_name', $permissionName)
            ->exists();
    }

    /**
     * Assign permission to role
     */
    public function givePermission(Permission|int $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;

        $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    /**
     * Remove permission from role
     */
    public function revokePermission(Permission|int $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;

        $this->permissions()->detach($permissionId);
    }
}

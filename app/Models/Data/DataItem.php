<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class DataItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'age',
        'status',
        'current_role',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    public function canEdit(array $userRoles): bool
    {
        return in_array($this->current_role, $userRoles, true);
    }
}

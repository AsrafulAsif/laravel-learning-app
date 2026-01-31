<?php

namespace App\Jobs\Privilege;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SoftDeleteRoleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $roleId;
    public int $userId;

    public function __construct(int $roleId, int $userId)
    {
        $this->roleId = $roleId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $now = now();

        DB::table('roles')
            ->where('id', $this->roleId)
            ->where('is_deleted', false)
            ->update([
                'is_active' => false,
                'is_deleted' => true,
                'deleted_by' => $this->userId,
                'deleted_at' => $now,
            ]);

        DB::table('role_user')
            ->where('role_id', $this->roleId)
            ->where('user_id', $this->userId)
            ->update([
                'is_active' => false,
                'is_deleted' => true,
                'deleted_by' => $this->userId,
                'deleted_at' => $now,
            ]);

        DB::table('role_permissions')
            ->where('role_id', $this->roleId)
            ->where('is_deleted', false)
            ->update([
                'is_active' => false,
                'is_deleted' => true,
                'deleted_by' => $this->userId,
                'deleted_at' => $now,
            ]);
    }
}

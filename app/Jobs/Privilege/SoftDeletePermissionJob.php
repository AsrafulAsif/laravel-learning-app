<?php

namespace App\Jobs\Privilege;

use App\Models\Privilege\Permission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SoftDeletePermissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $permissionId;
    public int $userId;

    public function __construct(int $permissionId, int $userId)
    {
        $this->permissionId = $permissionId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $now = now();

        DB::table('permissions')
            ->where('id', $this->permissionId)
            ->where('is_deleted', false)
            ->update([
                'is_active' => false,
                'is_deleted' => true,
                'deleted_by' => $this->userId,
                'deleted_at' => $now,
            ]);


        DB::table('role_permissions')
            ->where('role_id', $this->permissionId)
            ->where('is_deleted', false)
            ->update([
                'is_active' => false,
                'is_deleted' => true,
                'deleted_by' => $this->userId,
                'deleted_at' => $now,
            ]);
    }
}

<?php

namespace App\Services\Data;

use App\Models\Data\DataItem;
use App\Models\Data\WorkFlow;
use App\Services\Privilege\RoleService;
use Log;

class DataService
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function create(array $data): void
    {
        DataItem::create([
            ...$data,
            'status' => 'pending',
            'current_role' => 'role1',
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);
    }

    public function update(array $data, int $id): bool
    {
        $data = array_filter($data, fn($value) => !is_null($value));

        Log::info("Request Data:", $data);

        $roleNames = $this->roleService->getUserRolesName(auth()->user()->id);

        abort_if(
            empty($roleNames),
            401,
            "You don't have permission to update data flow",
        );


        $dataItem = DataItem::findOrFail($id);

        if ($dataItem->status === 'approved') {
            return false;
        }


        abort_if(!$dataItem->canEdit($roleNames),
            403,
            "You don't have permission to edit data flow"
        );

        $nextRole = WorkFlow::query()
            ->where('workflow_name_id', $dataItem->workflow_id)
            ->where('current_role', $dataItem->current_role)
            ->value('next_role');

        Log::info($nextRole);

        $dataItem->update([
            ...$data,
            'current_role' => $nextRole,
            'status' => $nextRole ? 'pending' : 'approved',
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);
        return true;
    }
}

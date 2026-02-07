<?php

namespace App\Services\Data;

use App\Models\Data\WorkFlow;
use App\Models\Data\WorkFlowName;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;

class WorkFlowService
{

    /**
     * @throws Throwable
     */
    public function create(array $data): void
    {
        DB::transaction(function () use ($data) {
            $workflowName = WorkFlowName::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'created_by' => auth()->id(),
            ]);

            foreach ($data['roles'] as $index => $role) {
                $next_role = $data['roles'][$index + 1] ?? null;

                $workflow = WorkFlow::query()
                    ->where('workflow_name_id', $workflowName->id)
                    ->where('current_role', $role)
                    ->where('next_role', $next_role)
                    ->first();

                if (!$workflow) {
                    WorkFlow::create([
                        'workflow_name_id' => $workflowName->id,
                        'current_role' => $role,
                        'next_role' => $data['roles'][$index + 1] ?? null,
                    ]);
                }
            }
        });
    }

    public function showWorkflow(int $workflowId): array
    {
        $rows = DB::table('workflow_names as wn')
            ->leftJoin('work_flows as wf', 'wf.workflow_name_id', '=', 'wn.id')
            ->where('wn.id', $workflowId)
            ->select(
                'wn.name',
                'wn.description',
                'wf.current_role',
                'wf.next_role'
            )
            ->get();

        abort_if($rows->isEmpty(), 404, 'Workflow not found');

        $roles = [];

        $map = $rows->keyBy('current_role');
        Log::info($map);
        $currentRole = $rows->first()->current_role;

        while ($currentRole) {
            $roles[] = $currentRole;
            $currentRole = $map[$currentRole]->next_role ?? null;
        }

        return [
            'name' => $rows->first()->name,
            'description' => $rows->first()->description,
            'roles' => $roles,
        ];
    }

    public function showWorkflowV2(int $workflowId): array
    {
        $workflow = WorkflowName::with('steps')
            ->findOrFail($workflowId);

        $roles = [];

        $stepMap = $workflow->steps->keyBy('current_role');

        Log::info($stepMap);
        $currentRole = $workflow->steps->first()->current_role ?? null;

        while ($currentRole) {
            $roles[] = $currentRole;
            $currentRole = $stepMap[$currentRole]->next_role ?? null;
        }

        return [
            'name' => $workflow->name,
            'description' => $workflow->description,
            'roles' => $roles,
        ];
    }

}

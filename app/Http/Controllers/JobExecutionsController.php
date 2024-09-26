<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecuteJobRequest;
use App\Jobs\DispatcherJob;
use App\Models\JobExecution;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Bus;

class JobExecutionsController extends Controller
{
    public function index(Request $request, Project $project): LengthAwarePaginator
    {
        $query = JobExecution::select([
            'job_executions.*',
            'job_batches.total_jobs',
            'job_batches.pending_jobs',
            'job_batches.failed_jobs',
            'job_batches.cancelled_at',
            'job_batches.finished_at',
        ])
            ->selectRaw('CASE WHEN job_batches.total_jobs > 0
                THEN ROUND((job_batches.total_jobs - job_batches.pending_jobs) / job_batches.total_jobs * 100)
            ELSE 0
            END AS progress')
            ->leftJoin('job_batches', 'job_batches.id', 'job_executions.batch_id')
            ->where('job_executions.project_id', $project->id);

        if ($request->has('search') && $request->search) {
            $query->where('job_executions.name', 'LIKE', '%'.$request->search.'%');
        }

        // Sort Column
        $sortColumn = $request->sortColumn
            ? 'job_executions.'.$request->sortColumn
            : 'job_executions.created_at';

        // Sort Direction
        $sortDirection = $request->sortDirection ?? 'DESC';

        //
        return $query->orderBy($sortColumn, $sortDirection)
            ->paginate($request->per_page);
    }

    public function show(JobExecution $jobExecution): JsonResponse
    {
        return response()->json([
            'jobExecution' => $jobExecution,
        ], 200);
    }

    public function executeJob(ExecuteJobRequest $request, Project $project): JsonResponse
    {
        $jobExecution = JobExecution::create([
            'project_id' => $project->id,
            'name' => $request->name ?? 'Job '.time(),
            'job' => $request->job,
            'params' => $request->params,
        ]);

        //
        DispatcherJob::dispatch($project, $jobExecution);

        //
        return response()->json([
            'jobExecution' => $jobExecution,
            'message' => 'Job Created Successfully',
        ], 200);
    }

    public function getBatch(string $batchId): JsonResponse
    {
        $batch = Bus::findBatch($batchId);

        return response()->json([
            'batch' => $batch,
            'message' => 'Successfully',
        ], 200);
    }

    public function jobsAvailable(): JsonResponse
    {
        $jobsAvailable = collect(config('jobs-available'))
            ->map(function ($job, $type) {
                return [
                    'value' => $type,
                    'title' => $job['title'],
                    'description' => $job['description'],
                    'file' => $job['file'],
                    'allow_params' => $job['allow_params'],
                ];
            })
            ->values();

        return response()->json([
            'jobsAvailable' => $jobsAvailable,
        ], 200);
    }
}

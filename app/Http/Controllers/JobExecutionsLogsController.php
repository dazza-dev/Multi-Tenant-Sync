<?php

namespace App\Http\Controllers;

use App\Models\JobExecution;
use App\Models\JobExecutionLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JobExecutionsLogsController extends Controller
{
    public function index(Request $request, JobExecution $jobExecution): LengthAwarePaginator
    {
        $query = JobExecutionLog::whereJobExecutionId($jobExecution->id);

        if ($request->has('search') && $request->search) {
            $query->where('tenant_name', 'LIKE', '%'.$request->search.'%');
        }

        return $query->orderBy(
            $request->sortColumn ?? 'created_at',
            $request->sortDirection ?? 'DESC'
        )
            ->paginate($request->per_page);
    }
}

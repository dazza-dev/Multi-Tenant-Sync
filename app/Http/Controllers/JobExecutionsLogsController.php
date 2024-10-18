<?php

namespace App\Http\Controllers;

use App\Exports\JobExecutionLogsExport;
use App\Models\JobExecution;
use App\Models\JobExecutionLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Excel;

class JobExecutionsLogsController extends Controller
{
    public function index(Request $request, JobExecution $jobExecution): LengthAwarePaginator
    {
        $query = JobExecutionLog::whereJobExecutionId($jobExecution->id);

        if ($request->has('search') && $request->search) {
            $query->where('tenant_name', 'LIKE', '%'.$request->search.'%');
        }

        if ($request->has('status') && ! is_null($request->status)) {
            $query->where('success', $request->status);
        }

        return $query->orderBy(
            $request->sortColumn ?? 'created_at',
            $request->sortDirection ?? 'DESC'
        )
            ->paginate($request->per_page);
    }

    public function export(Request $request, JobExecution $jobExecution)
    {
        return (new JobExecutionLogsExport(
            $jobExecution->id,
            $request->get('status', null),
            $request->get('search', null)
        ))->download('export.csv', Excel::CSV, ['Content-Type' => 'text/csv']);
    }
}

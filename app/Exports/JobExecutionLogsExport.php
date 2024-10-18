<?php

namespace App\Exports;

use App\Models\JobExecutionLog;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobExecutionLogsExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $jobExecutionId;

    private $status;

    private $search;

    public function __construct(int $jobExecutionId, ?int $status = null, ?string $search = null)
    {
        $this->jobExecutionId = $jobExecutionId;
        $this->status = $status;
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = JobExecutionLog::whereJobExecutionId($this->jobExecutionId);

        if ($this->search) {
            $query->where('tenant_name', 'LIKE', '%'.$this->search.'%');
        }

        if ($this->status) {
            $query->where('success', $this->status);
        }

        $jobs = $query->orderBy('created_at', 'DESC')->get();

        return $jobs->map(function ($score) {
            return [
                $score->tenant_name,
                $score->success ? 'Completed' : 'Failed',
                $score->response,
                $score->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tenant',
            'Status',
            'Response',
            'Date',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobExecutionLog extends Model
{
    protected $table = 'job_executions_logs';

    protected $fillable = [
        'job_execution_id',
        'tenant_name',
        'success',
        'response',
    ];

    protected $casts = [
        'success' => 'boolean',
    ];

    public function execution()
    {
        return $this->belongsTo(JobExecution::class, 'job_execution_id');
    }
}

<?php

namespace App\Jobs;

use App\Jobs\Middleware\TenantDatabaseMiddleware;
use App\Models\JobExecution;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class DispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;

    public $jobExecution;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project, JobExecution $jobExecution)
    {
        $this->project = $project;
        $this->jobExecution = $jobExecution;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Create The Job Batch
            $batch = Bus::batch([])
                ->name($this->jobExecution->name)
                ->allowFailures()
                ->dispatch();

            // Set Batch Id
            $this->jobExecution->update([
                'batch_id' => $batch->id,
            ]);

            // Set Project Connection
            setProjectConn($this->project);

            // Get Customers (tenant) Databases
            $customers = getAllDatabases(
                'cloud',
                $this->project->raw_query,
                $this->project->bindings ?? []
            );

            $jobFile = getJobFile($this->jobExecution->job);

            // Add Middleware
            Bus::pipeThrough([
                new TenantDatabaseMiddleware,
                fn ($job, $next) => $next($job),
            ]);

            foreach ($customers->chunk(1000) as $databases) {
                $jobs = [];
                foreach ($databases as $database) {
                    $jobs[] = new $jobFile(
                        $this->jobExecution->id,
                        (array) $database,
                        $this->jobExecution->params
                    );
                }
                $batch->add($jobs);
            }
        } catch (\Throwable $e) {
            $this->jobExecution->update([
                'status' => 'failed',
                'response' => $e->getMessage(),
            ]);
        }
    }
}

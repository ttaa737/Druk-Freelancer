<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;

class BackfillJobDeadlines extends Command
{
    protected $signature = 'jobs:backfill-deadlines';
    protected $description = 'Backfill proposal and project deadlines for existing jobs';

    public function handle()
    {
        $this->info('Starting backfill of job deadlines...');

        Job::chunk(100, function ($jobs) {
            foreach ($jobs as $job) {
                $changed = false;

                // If proposal deadline (expires_at) is missing, set a sensible default: created_at + 30 days
                if (is_null($job->expires_at)) {
                    $job->expires_at = $job->created_at ? $job->created_at->copy()->addDays(30) : now()->addDays(30);
                    $changed = true;
                }

                // If job_deadline is missing but duration_days exists, compute from expires_at (proposal deadline)
                if (is_null($job->job_deadline) && $job->duration_days) {
                    $base = $job->expires_at ?? ($job->created_at ?? now());
                    $job->job_deadline = $base->copy()->addDays((int) $job->duration_days);
                    $changed = true;
                }

                if ($changed) {
                    $job->save();
                }
            }
        });

        $this->info('Backfill completed.');
        return 0;
    }
}

<?php

namespace App\Jobs;

use App\Models\AdminLogModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdminLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    private $before;
    private $after;
    private $except;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $before = [], $after = [], $except = ['create_time', 'update_time'])
    {
        $this->queue = config('queuejob.admin_log_queue');
        $this->data = $data;
        $this->before = $before;
        $this->after = $after;
        $this->except = $except;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AdminLogModel $adminLogModel)
    {

        try {
            $adminLogModel->writeLog($this->data, $this->before, $this->after, $this->except);
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
        }
    }
}

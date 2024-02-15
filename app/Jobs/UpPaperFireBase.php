<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// php artisan make:job UpPaperFireBase
class UpPaperFireBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paper_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paper_id)
    {
        //
        $this->paper_id = $paper_id;
    }

    /**
     * Execute the job.
     *
     * @return voidphp 
     */
    public function handle()
    {

    }
}

<?php

namespace App\Jobs;

use App\Api\PaperApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
     * @return void
     */
    public function handle(PaperApi $paperApi)
    {
        $paperApi->addPapersCategory($this->paper_id);
        $paperApi->upFirebaseComments($this->paper_id);
        $paperApi->upPaperInfo($this->paper_id);
        $paperApi->upContentFireStore($this->paper_id);
    }
}

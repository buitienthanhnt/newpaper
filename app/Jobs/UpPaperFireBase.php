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
    protected $firebaseImage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paper_id, $firebaseImage)
    {
        //
        $this->paper_id = $paper_id;
        $this->firebaseImage = $firebaseImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PaperApi $paperApi)
    {
        $_paper = $paperApi->getDetail($this->paper_id);

        $paperApi->addPapersCategory($_paper, $this->firebaseImage);
        $paperApi->upFirebaseComments($_paper);
        $paperApi->upPaperInfo($_paper);
        $paperApi->upContentFireStore($_paper);
        $paperApi->upPaperWriter($_paper);
    }
}

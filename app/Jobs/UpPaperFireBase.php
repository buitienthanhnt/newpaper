<?php

namespace App\Jobs;

use App\Api\PaperApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Thanhnt\Nan\Helper\LogTha;

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
    public function handle(PaperApi $paperApi, LogTha $logTha)
    {
        $_paper = $paperApi->getDetail($this->paper_id);
        $logTha->logFirebase('info', '<================ ' . $_paper->id . ' ================>');
        /**
         * add paper to firebase category.
         */
        $paperApi->addPapersCategory($_paper, $this->firebaseImage);
        /**
         * up comment of paper to firebase.
         */
        $paperApi->upFirebaseComments($_paper);
        /**
         * up paper info to firebase storage(like, heart, viewcount, comment).
         */
        $paperApi->upPaperInfo($_paper);
        /**
         * up paper content to firestorage firebase.
         */
        $paperApi->upContentFireStore($_paper);
        /**
         * up paper to writer in realtime database firebase
         */
        $paperApi->upPaperWriter($_paper, $this->firebaseImage);
        $logTha->logFirebase('info', '<================ ' . $_paper->id . ' ================>');
    }
}

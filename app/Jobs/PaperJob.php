<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * Run: php artisan queue:work
     *
     * @return void
     */
    public function handle()
    {
        $channel = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/tha/nan_queue.log'),
        ]);
        Log::stack([$channel])->info('demo queue in custom nan_queue log file!');

        try {
            // Mail::to('buisuphu01655@gmail.com')->send(new UserEmail());  // php artisan make:mail UserEmail

            Mail::send('welcome', [], function ($message) {
                $message->from('buitienthanhnt@gmail.com', "tha nan");
                $message->to("thanh.bui@jmango360.com", 'user1');
                $message->subject("demo by send mail laravel newpaper");
            }
            );
        } catch (\Throwable $th) {
            //throw $th;
            echo ($th->getMessage());
            return;
        }

    }
}

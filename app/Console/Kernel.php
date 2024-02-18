<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * https://viblo.asia/p/tim-hieu-ve-task-scheduling-trong-laravel-aWj53O6w56m
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\IndexCommand', // add by tha
        'Thanhnt\Nan\Commands\DemoCommand',  // add for class in custom pack
        'App\Console\Commands\PaperFirebase', // add by tha for sync paper data from firebase
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // chạy: php artisan schedule:run để chạy thủ công
        // hoặc: 
        // crontab -e để cài đặt
        // mở: * * * * * php /var/www/html/laravel1/artisan schedule:run 
        // để chạy tự động trên ubuntu.
        
        // $schedule->command('inspire')->hourly();
        $schedule->command('paper:index')->dailyAt('16:05'); // cron job trong laravel: add by tha
        $schedule->command('paperFirebase:sync')->everySixHours(); // cron job trong laravel: add by tha
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

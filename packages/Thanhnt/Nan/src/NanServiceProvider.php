<?php
namespace Thanhnt\Nan;

use Illuminate\Support\ServiceProvider;

class NanServiceProvider extends ServiceProvider {
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
    public function register()
    {

    }
}

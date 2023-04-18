<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        try {
            $topcategory = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY)->firstOr(function(){
                return null;
            });

            if ($topcategory) {
                $list_category = Category::find(explode("&", $topcategory->value));
                View::share("topcategory", $list_category);
            }
        }catch (\Exception $e){

        }
    }
}

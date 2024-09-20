<?php

namespace App\Providers;

use App\Helper\Nan;
use App\Models\Category;
use App\Models\ConfigCategory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use Nan;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // đăng ký 1 blade function cho view.
        // theo đó trong blade template khi gọi function này(render) nó sẽ chạy vào đây
        Blade::directive('render', function ($component) {

            //  * @render(\App\ViewBlock\TopCategory::class)
            return "<?php echo (app($component))->toHtml(); ?>";
            // $html = (app($component))->toHtml();
            // return $html;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('config_categories')) {
            try {
                $topcategory = ConfigCategory::where("path", ConfigCategory::TOP_CATEGORY)->firstOr(function () {
                    return null;
                });
                if ($topcategory) {
                    $list_category = Category::find(explode("&", $topcategory->value));
                    View::share("topcategory", $list_category);
                }
                $custom_css = DB::table($this->coreConfigTable())->where("name", "=", "custom_css")->first('value');
                View::share("custom_css", $custom_css->value ?? '');
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }
}

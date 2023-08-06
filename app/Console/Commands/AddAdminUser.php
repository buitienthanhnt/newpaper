<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * https://viblo.asia/p/create-a-custom-artisan-command-laravel-55-6J3Zg2QAKmB
 */
class AddAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminUser:add {admin_user} {admin_email} {admin_password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add new admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $adminUser = new AdminUser(
                [
                    "name" => $this->argument("admin_user"),
                    "email" => $this->argument("admin_email"),
                    "password" => Hash::make($this->argument("admin_password")),
                    "active" => 1,
                    "created_at" => (string) Carbon::now()->getTimestamp(),
                    "updated_at" => (string) Carbon::now()->getTimestamp()
                ]
            );

            $rootAdmin = Permission::where("label", "root")->first();
            if (!$rootAdmin) {
                $rootAdmin = new Permission(["label" => "root"]);
                $rootAdmin->save();
            }

            $res = $adminUser->save();
            $adminUser->savePermissions($adminUser, [$rootAdmin->id]);
            $this->line("add new adminUser success!");
        } catch (\Throwable $th) {
            $this->line("add new adminUser fail! message: ".$th->getMessage());
        }
    }

    protected function insertByBD() {
        try {
            DB::beginTransaction();

            //update publish_time column
            DB::table('admin_users')->update([
                "name" => $this->argument("admin_user"),
                "email" => $this->argument("admin_email"),
                "password" => Hash::make($this->argument("admin_password")),
                "active" => 1
            ]);

            DB::commit();

            //send output to the console
            $this->info('Success!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error($e->getMessage());
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaperType extends Migration
{
    const CONTEN_TYPE = "content";
    const CAROUSEL_TYPE = "carousel";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('papers', function($table) {
            $table->string('type')->default(self::CONTEN_TYPE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('papers', function($table) {
            $table->dropColumn('type');
        });
    }
}

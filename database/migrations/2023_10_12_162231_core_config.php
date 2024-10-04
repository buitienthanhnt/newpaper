<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreConfig extends Migration
{
    use \App\Helper\Nan;

    const ATTR_NAME = 'name';
    const ATTR_VALUE = 'value';
    const ATTR_DESCRIPTION = 'description';
    const ATTR_TYPE = 'type';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::coreConfigTable(), function (Blueprint $table) {
            $table->string(self::ATTR_NAME);
            $table->string(self::ATTR_VALUE)->nullable();
            $table->text(self::ATTR_DESCRIPTION)->nullable(true);
            $table->text(self::ATTR_TYPE)->default('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists(self::coreConfigTable());
    }
}

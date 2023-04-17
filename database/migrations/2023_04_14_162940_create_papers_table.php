<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable(false);
            $table->string("url_alias")->nullable(true);
            $table->string("short_conten")->nullable(true);
            $table->text("conten")->nullable(true);
            $table->boolean("active");
            $table->boolean("show")->default(1);
            // $table->boolean("hot")->default(false);
            // $table->boolean("news")->default(false);
            $table->time("show_time")->nullable(true);
            $table->string("image_path");
            $table->string("tag")->nullable(true);
            $table->boolean("auto_hide")->default(0);
            $table->string("writer")->nullable(true);
            $table->boolean("show_writer")->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papers');
    }
}

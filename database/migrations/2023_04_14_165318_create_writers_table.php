<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWritersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writers', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(false);
            $table->string("email")->unique("email")->nullable(false);
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->string("group")->nullable();
            $table->string("image_path")->nullable();
            $table->string("name_alias")->unique("name_alias")->nullable();
            $table->boolean("active")->default(false);
            $table->string("good")->nullable();
            $table->timestamp("date_of_birth")->nullable(false);
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
        Schema::dropIfExists('writers');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTables extends Migration
{
    public function up()
    {
        Schema::create('pages_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rev')->index();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->longText('content');
            $table->integer('writer_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pages_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pages_page_id')->index();
            $table->integer('rev')->index();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->longText('content');
            $table->integer('writer_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('pages_pages');
        Schema::drop('pages_histories');
    }
}

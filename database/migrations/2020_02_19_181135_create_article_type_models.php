<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTypeModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('article_type_models', function (Blueprint $table) {
//            $table->bigIncrements('at_id')->comment('文章分类id');
//            $table->string('article_type_name','200')->comment('文章分类');
//            $table->dateTime('deleted_at')->default(null)->comment('软删除');
//            $table->char('status',1)->default('1')->comment('1正常，2下架');
//            $table->unsignedBigInteger('pid')->comment('父id');
//            $table->timestamps();
//        });
        Schema::rename('article_type_models','fb_article_type_models');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_type_models');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTypeModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_article_type_models', function (Blueprint $table) {
            $table->tinyIncrements('at_id')->comment('文章分类id');
            $table->string('type_name',30)->default('')->comment('文章分类名称');
            $table->char('status',1)->default('1')->comment('状态，1正常2冻结');
            $table->unsignedTinyInteger('pid',0);
            $table->unsignedTinyInteger('sort');
            $table->dateTime('deleted_at');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
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

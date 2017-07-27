<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'blogs', function( Blueprint $table) {
            $table->increments( 'id' );
            $table->string('b_flag')->unique()->comment( '标签' );
            $table->string('b_title')->default( '' )->comment( '标题' );
            $table->string('b_thumb')->default('');
            $table->integer('b_cat_id')->default( 0 )->comment( '分类ID' );
            $table->integer('user_id')->comment( '用户ID' );
            $table->text('b_content')->nullable();
            $table->text('b_md')->nullable();
            $table->integer('b_views')->default( 0 )->comment( '查看次数' );
            $table->integer('b_comments')->default( 0 )->comment( '评论次数' );
            $table->string('b_ip')->default( '' )->comment( '提交IP' );
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
        Schema::dropIfExists( 'blogs' );
    }
}

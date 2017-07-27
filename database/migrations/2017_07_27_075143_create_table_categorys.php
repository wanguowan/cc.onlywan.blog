<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCategorys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'categorys', function( Blueprint $table) {
            $table->increments( 'id' );
            $table->string( 'cat_name', 128 )->default( '' )->comment( '分类名' );
            $table->integer( 'cat_parent' )->default( 0 )->comment( '分类父级' );
            $table->string( 'cat_flag', 64 )->unique()->comment( '分类标识');
            $table->string( 'cat_desc', 256 )->default( '' )->comment( '分类描述' );
            $table->string( 'cat_ip', 64 )->default( '' )->comment( '创建IP' );
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
        Schema::dropIfExists( 'categorys' );
    }
}

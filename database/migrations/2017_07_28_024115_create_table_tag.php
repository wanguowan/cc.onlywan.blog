<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'tag_name' )->default( '' );
            $table->string( 'tag_flag' )->default( '' );
            $table->timestamps();
        });

        Schema::create('blogs_tags', function( Blueprint $table )
        {
            $table->integer( 'blog_id' )->unsigned()->index();
            $table->integer( 'tag_id' )->unsigned()->index();
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
        Schema::dropIfExists('tags');
        Schema::dropIfExists('blogs_tags');
    }
}

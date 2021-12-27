<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('blog_id');
            $table->string('title');
            $table->longText('slug');
            $table->longText('excerpt');
            $table->longText('content');
            $table->longText('image');
            $table->longText('url');
            $table->timestamps();

            $table->index('author_id');
            $table->index('blog_id');
            $table->index('slug');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

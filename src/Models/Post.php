<?php

namespace AbdelrahmanMedhat\BlogsScraper\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'url',
        'category_id',
        'blog_id',
        'category_id',
        'author_id',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class,'blog_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class,'author_id');
    }

    
    public function tags()
    {
        return $this->belongsToMany(Tag::class,'posts_tags','post_id','tag_id');
    }
}

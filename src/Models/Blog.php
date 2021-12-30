<?php

namespace AbdelrahmanMedhat\BlogsScraper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'domain'
    ];
    
    public function posts(){
        return $this->hasMany(Post::class,'blog_id');
    }
}

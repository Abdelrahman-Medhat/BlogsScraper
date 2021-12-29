<?php

namespace AbdelrahmanMedhat\BlogsScraper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public function posts(){
        return $this->hasMany(Post::class,'author_id');
    }
}

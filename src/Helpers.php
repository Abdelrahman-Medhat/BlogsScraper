<?php 

namespace AbdelrahmanMedhat\BlogsScraper;

use AbdelrahmanMedhat\BlogsScraper\Models\Post;
use AbdelrahmanMedhat\BlogsScraper\Models\Category;
use AbdelrahmanMedhat\BlogsScraper\Models\Author;
use AbdelrahmanMedhat\BlogsScraper\Models\Blog;
use AbdelrahmanMedhat\BlogsScraper\Models\Tag;
use Goutte;

trait Helpers{
    public $author;
    public $blog;
    public $category; 
    public $tags_ids;


    public function createSlug($title){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $title);
        return $slug;
    }

    public function postNotExist($title){
        $post = Post::where([
            'slug' => $this->createSlug($title)
        ])->first();
        return empty($post) ? true : false;
    }
    
    public function insertCategoryIfNotExist($categoryName){
        $category = Category::where('slug' , $this->createSlug($categoryName))->first();
        if(empty($category)){
            $category = new Category;
            $category->name = $categoryName;
            $category->slug = $this->createSlug($categoryName);
            $category->save();
            $this->category = $category;
        }
    }

    public function insertBlogIfNotExist($BlogName){
        $blog = Blog::where('slug' , $this->createSlug($this->blogName))->first();
        if(empty($blog)){
            $blog = new Blog;
            $blog->name = $this->blogName;
            $blog->slug = $this->createSlug($this->blogName);
            $blog->domain = $this->blog::$blogUrl;
            $blog->save();
            $this->blog = $blog;
        }
    }

    public function insertAuthorIfNotExist($authorName){
        $author = Author::where('slug' , $this->createSlug($authorName))->first();
        if(empty($author)){
            $author = new Author;
            $author->name = $authorName;
            $author->slug = $this->createSlug($authorName);
            $author->save();
            $this->author = $author;
        }
    }

    public function insertTagsIfNotExist($tags){
        $tags_ids = [];
        foreach($tags as $nodeTag){
            $tag = Tag::where('slug' , $this->createSlug($nodeTag))->first();
            if(empty($tag)){
                $tag = new Tag;
                $tag->name = $nodeTag;
                $tag->slug = $this->createSlug($nodeTag);
                $tag->save();
            }
            $tags_ids []= $tag->id;
        }
        $this->tags_ids = $tags_ids;
    }


    public function insertPost($data){
        $post = Post::create([
            'author_id' => $this->author->id,
            'blog_id' => $this->blog->id,
            'category_id' => $this->category->id,
            'title' => $data['title'] ,
            'slug' => $this->createSlug($data['title']),
            'excerpt' => substr($data['content'], 0, 50),
            'content' => $data['content'],
            'image' => $data['image'] ,
            'url' => $data['url'] 
        ]);
        
        $post = Post::findOrFail($post->id);
        $post->tags()->attach($this->tags_ids);
    }

    
    public function blogParser($blogName){
        $websiteClass = $blogName.'Parser';
        $className ='App\Parsers\\'.$websiteClass;
        return new $className();
    }

    public function getPostPageHtml($postPageLink){
        return Goutte::request('GET', $postPageLink);
    }
    
}


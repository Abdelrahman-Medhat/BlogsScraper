<?php

namespace AbdelrahmanMedhat\BlogsScraper;
use AbdelrahmanMedhat\BlogsScraper\Models\Post;
use AbdelrahmanMedhat\BlogsScraper\Models\Category;
use AbdelrahmanMedhat\BlogsScraper\Models\Author;
use AbdelrahmanMedhat\BlogsScraper\Models\Blog;
use AbdelrahmanMedhat\BlogsScraper\Models\Tag;
use Goutte;

class BlogsScraper
{   
    public $blog; 
    public $websiteName; 
    public $tag; 
    public $pages = 0; 
    public $post_index = 0; 

    public  function createSlug($title){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $title);
        return $slug;
    }

    public function scrape($websiteName, $tag, $pages){
        $this->websiteName =  $websiteName;
        $this->tag =  $tag;
        $this->pages =  $pages;

        $websiteClass = $websiteName.'Parser';
        $className ='App\Parsers\\'.$websiteClass;
        $this->blog =  new $className();
        
        foreach($pages as $page){
            $this->blog->posts(Goutte::request('GET', 
                str_replace('{{page}}',$page,str_replace('{{tag}}',$tag,$this->blog::$websiteQuery))
            ))->each(function ($node) {

                $post = Post::where([
                    'slug' => $this->createSlug($this->blog->postTitle($node))
                ])->first();

                if(empty($post)){

                    $getPost = Goutte::request('GET', $this->blog->postLink($node));

                    $category = Category::where('slug' , $this->createSlug($this->blog->postCategory($node)))->first();
                    if(empty($category)){
                        $category = new Category;
                        $category->name = $this->blog->postCategory($node);
                        $category->slug = $this->createSlug($this->blog->postCategory($node));
                        $category->save();
                    }

                    $blog = Blog::where('slug' , $this->createSlug($this->websiteName))->first();
                    if(empty($blog)){
                        $blog = new Blog;
                        $blog->name = $this->websiteName;
                        $blog->slug = $this->createSlug($this->websiteName);
                        $blog->domain = $this->blog::$websiteUrl;
                        $blog->save();
                    }
                    
                    $author = Author::where('slug' , $this->createSlug($this->blog->postAuthor($getPost)))->first();
                    if(empty($author)){
                        $author = new Author;
                        $author->name = $this->blog->postAuthor($getPost);
                        $author->slug = $this->createSlug($this->blog->postAuthor($getPost));
                        $author->save();
                    }

                    $tags = $this->blog->postTags($getPost);
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
                    
                    $post = Post::create([
                        'author_id' => $author->id,
                        'blog_id' => $blog->id,
                        'category_id' => $category->id,
                        'title' => $this->blog->postTitle($node),
                        'slug' => $this->createSlug($this->blog->postTitle($node)),
                        'excerpt' => $this->blog->postExcerpt($node),
                        'content' => $this->blog->postContent($getPost),
                        'image' => $this->blog->postImage($node),
                        'url' => $this->blog->postLink($node)
                    ]);
                    
                    $post = Post::findOrFail($post->id);
                    $post->tags()->attach($tags_ids);
                }
                $this->post_index++;
            });

        }
    }

    public function sync($websiteName,$tag, $pages){
        $this->scrape($websiteName, $tag, $pages);
    }

}
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
    public $scraped_posts = [];
    public $blog; 
    public $post_index = 0; 

    public  function createSlug($title){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $title);
        return $slug;
    }

    public function scrape($websiteName, $tag, $page){
        $websiteClass = $websiteName.'Parser';
        $className ='App\Parsers\\'.$websiteClass;
        $this->blog =  new $className();
        
        $this->blog->posts(Goutte::request('GET', 
            str_replace('{{page}}',$page,str_replace('{{tag}}',$tag,$this->blog::$websiteQuery))
        ))->each(function ($node) {
            
            $post = Post::where([
                'slug' => $this->createSlug($this->blog->postTitle($node))
            ])->first();

            if(empty($post)){
                $blog = Blog::where('slug' , $this->createSlug($websiteName))->first();
                if(empty($blog)){
                    $blog = new Blog;
                    $blog->name = $websiteName;
                    $blog->slug = $this->createSlug($websiteName);
                    $blog->domain = $this->blog::$websiteUrl;
                    $blog->save();
                }
    
                $author = Author::where('slug' , $this->createSlug($this->blog->postAuthorSelector($getPost)))->first();
                if(empty($author)){
                    $author = new Author;
                    $author->name = $this->blog->postAuthorSelector($getPost);
                    $author->slug = $this->createSlug($this->blog->postAuthorSelector($getPost));
                    $author->save();
                }
    
                $category = Category::where('slug' , $this->createSlug($this->blog->postCategory($node)))->first();
                if(empty($category)){
                    $category = new Category;
                    $category->name = $this->blog->postCategory($node);
                    $category->slug = $this->createSlug($this->blog->postCategory($node));
                    $category->save();
                }
    
                $tags = $this->blog->postTagsSelector($getPost);
                $tags_ids = [];
                foreach($tags as $nodeTag){
                    $tag = Tag::where('slug' , $this->createSlug($nodeTag['slug']))->first();
                    if(empty($tag)){
                        $tag = new Tag;
                        $tag->name = $nodeTag['name'];
                        $tag->slug = $this->createSlug($nodeTag['slug']);
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
                    'content' => $this->blog->postContentSelector($getPost),
                    'image' => $this->blog->postImage($node),
                    'url' => $this->blog->postLink($node)
                ]);
                
                $post = Post::findOrFail($post->id);
                $post->tags()->attach($tags_ids);
            }
            $this->post_index++;
        });
        
        return $this->scraped_posts;
    }

    public function sync($websiteName){
        return $this->scrape($websiteName, 'biology', 2);
    }

}
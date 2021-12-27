<?php

namespace AbdelrahmanMedhat\BlogsScraper;
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

        $websiteClass = $websiteName;
        $className ='App\Parsers\\'.$websiteClass.'Parser';
        $this->blog =  new $className();
        
        $this->blog->posts(Goutte::request('GET', 
            str_replace('{{page}}',$page,str_replace('{{tag}}',$tag,$this->blog::$websiteQuery))
        ))->each(function ($node) {
            
            // Get Post link
            $this->scraped_posts[$this->post_index] ['link']= $this->blog->postLink($node);

            // Get Post Title
            $this->scraped_posts[$this->post_index] ['title']= $this->blog->postTitle($node);

            // Get Post Slug
            $this->scraped_posts[$this->post_index] ['slug']= $this->createSlug($this->blog->postTitle($node));

            // Get Image
            $this->scraped_posts[$this->post_index] ['image']= $this->blog->postImage($node);
            
            // Get Post Categories
            $this->scraped_posts[$this->post_index]['categories'] = $this->blog->postCategories($node) ;

            // Get Post Excerpt
            $this->scraped_posts[$this->post_index] ['excerpt']= $this->blog->postExcerpt($node);

            
            // Get post
            $post = Goutte::request('GET', $this->blog->postLink($node));

            // Get post author
            $this->scraped_posts[$this->post_index] ['author']= $this->blog->postAuthorSelector($post);
            
            // Get post content
            $this->scraped_posts[$this->post_index] ['content']= $this->blog->postContentSelector($post);

            // Get post tags
            $this->scraped_posts[$this->post_index]['tags'] = $this->blog->postTagsSelector($post);
            

            $this->post_index++;
        });
        
        return $this->scraped_posts;
    }

    public function sync($websiteName){
        return $this->scrape($websiteName, 'biology', 2);
    }
}
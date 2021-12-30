<?php

namespace AbdelrahmanMedhat\BlogsScraper;

use AbdelrahmanMedhat\BlogsScraper\Helpers;
use Goutte;

class BlogsScraper
{   

    use Helpers;

    public $blog; 
    public $blogName; 
    public $tag; 
    public $pages = 0; 

    public function scrape($blogName, $tag, $pages){
        ini_set('max_execution_time', 1000);
        $this->blogName =  $blogName;
        $this->tag =  $tag;
        $this->pages =  $pages;

        $this->blog =  $this->blogParser($blogName);
        
        foreach($pages as $page){
            $this->blog->posts(Goutte::request('GET', 
                str_replace('{{page}}',$page,str_replace('{{tag}}',$tag,$this->blog::$blogQuery))
            ))->each(function ($node) {

                if($this->postNotExist(
                    $this->blog->postTitle($node)
                )){   

                    $postPage = $this->getPostPageHtml($this->blog->postLink($node));

                    $this->insertCategoryIfNotExist(
                        $this->blog->postCategory($node)
                    );

                    $this->insertBlogIfNotExist(
                        $this->blogName
                    );

                    $this->insertAuthorIfNotExist(
                        $this->blog->postAuthor($postPage)
                    );
                    
                    $this->insertTagsIfNotExist(
                        $this->blog->postTags($postPage)
                    );
                    
                    $data = [
                        'title' => $this->blog->postTitle($node),
                        'content' => $this->blog->postContent($getPost),
                        'image' => $this->blog->postImage($node),
                        'url' => $this->blog->postLink($node)
                    ];
                    $this->insertPost($data);
                }
            });

        }
    }

    public function sync($blogName,$tag, $pages){
        $this->scrape($blogName, $tag, $pages);
    }

}

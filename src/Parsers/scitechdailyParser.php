<?php

namespace App\Parsers;

class scitechdailyParser 
{   
    public static $blogName = 'SciTechDaily';

    public static $blogUrl = 'https://scitechdaily.com';

    public static $blogLogo = 'https://scitechdaily.com/images/cropped-scitechdaily-amp60.png';

    public static $blogQuery = 'https://scitechdaily.com/news/{{tag}}/amp/page/{{page}}/';

    public function posts($html){
        return $html->filter('.listing-item');
    }

    public function postLink($node){
        return $node->filter('.post-title a')->attr('href');
    }

    public function postTitle($node){
        return $node->filter('.post-title a')->text();
    }
    
    public function postImage($node){
        return $node->filter('.post-thumbnail amp-img')->attr('src');
    }

    public function postCategory($node){
        return $node->filter('.post-categories li a')->text();
    }

    public function postAuthor($post){
        return $post->filter('.post-author')->text();
    }

    public function postContent($post){
        return str_replace('amp-img', 'img', $post->filter('.post-content')->html());
    }

    public function postTags($post){
        $GLOBALS['postTags'] = [];

        $post->filter('.tags a')->each(function ($tagsNodes) {
            $GLOBALS['postTags'] []= $tagsNodes->text() ;
        });

        return $GLOBALS['postTags'];
    }
    
}

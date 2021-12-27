<?php

namespace App\Parsers;

class scitechdailyParser 
{   
    public static $websiteName = 'SciTechDaily';

    public static $websiteUrl = 'https://scitechdaily.com';

    public static $websiteLogo = 'https://scitechdaily.com/images/cropped-scitechdaily-amp60.png';

    public static $websiteCategories = [
        'biology',
        // 'chemistry',
        // 'earth',
        // 'health',
        // 'physics',
        // 'science',
        // 'space',
        // 'technology'
    ];

    public static $websiteQuery = 'https://scitechdaily.com/news/{{tag}}/amp/page/{{page}}/';

    public function posts($postsContainer = null){
        return $postsContainer->filter('.listing-item');
    }

    public function postTitle($node){
        return $node->filter('.post-title a')->text();
    }
    
    public function postLink($node){
        return $node->filter('.post-title a')->attr('href');
    }
    
    public function postImage($node){
        return $node->filter('.post-thumbnail amp-img')->attr('src');
    }

    public function postCategories($node){
        $categoriesTags = [];

        $node->filter('.post-categories li a')->each(function ($categoriesNode) {
            $postCategories []= $categoriesNode->text() ;
        });

        return $categoriesTags;
    }

    public function postExcerpt($node){
        return $node->filter('.post-excerpt p')->text();
    }

    public function postAuthorSelector($post){
        return $post->filter('.post-author')->text();
    }

    public function postContentSelector($post){
        return str_replace('amp-img', 'img', $post->filter('.post-content')->html());
    }

    public function postTagsSelector($post){
        $postTags = [];

        $post->filter('.tags a')->each(function ($tagsNode) {
            $postTags []= $tagsNode->text() ;
        });

        return $postTags;
    }
    
}


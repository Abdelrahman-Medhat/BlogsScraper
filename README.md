
# Blogs Scraper

This package will help you sync posts from popular blogs on the Internet.


## Authors

- [Abdelrahman Medhat](https://github.com/Abdelrahman-Medhat)


## 1 ) Installation

#### **1) Install package**

Run bellow command in terminal for base path of laravel project
```bash
composer require abdelrahmanmedhat/blogsscraper
```

#### **2) Add Service Provider** 

Open config/app.php then add below line into providers array

```php
AbdelrahmanMedhat\BlogsScraper\BlogsScraperServiceProvider::class
```
 
#### **3) Publish package in project**
Run bellow command in terminal for base path of laravel project
```bash
php artisan vendor:publish --provider="AbdelrahmanMedhat\BlogsScraper\BlogsScraperServiceProvider"
```  

#### **4) Connect to database**
Make sure you are connect laravel project with database

#### **5) Publish package tables to database**
Run bellow command in terminal for base path of project
```bash
php artisan migrate
``` 
## 2 ) Parser


#### **1) Parsers folder**
In this folder `app/Parsers`  we will put any parser here that you will create from artisan command.

#### **2) Create your first parser**
• Open terminal on base path of project.

• Copy bellow command and paste it on terminal.

• Replace : `blog-name` from command with blog name you will scrape data from it.

• Run bellow command.

• You will see new parser in Parsers folder at this path `app/Parsers`.

```bash
php artisan create:parser blog-name
```


#### **3) Parser architecture**
• Go to this path : `app/Parsers` .

• Open this parser `scitechdailyParser.php` .

**In this variable you need define blog name.**
```php
public static $blogName = 'SciTechDaily';
```
­

---
­

**In this variable you need define home blog url.**
```php
public static $blogUrl = 'https://scitechdaily.com';
```
­

---
­

**In this variable you need define blog logo.**
```php
public static $blogLogo = 'https://scitechdaily.com/images/cropped-scitechdaily-amp60.png';
```

---
­


**In bellow variable you need define blog query that we will scrape data from it**

**And you need to put : `{{tag}}` instead of tag name and put : `{{page}}` instead of page number.** 

**Before : `https://scitechdaily.com/news/technology/amp/page/2/`** 

**After : `https://scitechdaily.com/news/{{tag}}/amp/page/{{page}}/`** 

```php
    public static $blogQuery = 'https://scitechdaily.com/news/{{tag}}/amp/page/{{page}}/';
```
­

---
­

**In this function you need to define all posts html nodes from class of post html div .**
```php
    public function posts($html){
        return $html->filter('.listing-item');
    }
```
| ***Notice*** | =>  `$html` this will return html of posts page.

| ***Notice*** | =>  you can use `->filter('selector here')` to extract another nodes or text or ...etc from `$html` or from `$node`.
­

---
­

**In this function you need to define post link .**
```php
    public function postLink($node){
        return $node->filter('.post-title a')->attr('href');
    }
```

| ***Notice*** | =>  `$node` this will return html of post node in posts page.

| ***Notice*** | =>  you can use `->attr('attribute here')` to get value from attribute like we do above on href.
­

---
­

**In this function you need to define post excerpt .**
```php
    public function postExcerpt($node){
        return $node->filter('.post-excerpt p')->text();
    }
```

| ***Notice*** | =>  you can use `->text()` to get text from html element like we do above.
­

---
­

**In this function you need to define post title .**
```php
    public function postTitle($node){
        return $node->filter('.post-title a')->text();
    }
```
­

---
­

**In this function you need to define post image .**
```php
    public function postImage($node){
        return $node->filter('.post-thumbnail amp-img')->attr('src');
    }
```
­

---
­

**In this function you need to define post category .**
```php
    public function postCategory($node){
        return $node->filter('.post-categories li a')->text();
    }
```
­

---
­

**In this function you need to define post author .**
```php
    public function postAuthor($post){
        return $post->filter('.post-author')->text();
    }
```
| ***Notice*** | =>  `$post` this will return html of post inner page.
­

---
­

**In this function you need to define post content .**

we replace any `amp-img` with `img` in post content because blog we scrape from it ,
replace img tag with amp-img and if we use it with amp-img the image will not load on your blog we need to change it 
to img tag to browser can load it .

```php
    public function postContent($post){
        return str_replace('amp-img', 'img', $post->filter('.post-content')->html());
    }

```
| ***Notice*** | =>  you can use `->html()` to get html value of node.
­

---
­

**In this function you need to define post tags .**
```php
    public function postTags($post){
        $GLOBALS['postTags'] = [];

        $post->filter('.tags a')->each(function ($tagsNodes) {
            $GLOBALS['postTags'] []= $tagsNodes->text() ;
        });

        return $GLOBALS['postTags'];
    }
```
| ***Notice*** | =>  you can use below method to loop on all element : `<a>` inside element have class : `.tags`  like what we do on above function
```
    $nodes->each(function ($node) {
        // use $node here
    });
``` 

­| ***Notice*** | =>  you need return all tags in array.


---
­

­

## 3 ) Scraping

#### Sync all posts from specific blog.

```php
    use AbdelrahmanMedhat\BlogsScraper\BlogsScraper;

    $blog_name = 'scitechdaily';
    $tag_name = 'technology';
    $pages = [1,2,3];

    $BlogsScraper =  new BlogsScraper;
    $BlogsScraper->sync($blog_name,$tag_name,$pages);

```
­| ***Notice*** | =>  you also can use this script to corn job on server With a specific schedule.

---
­


## 4 ) Database Relationships

#### Get all posts with relationships.

```php
    use AbdelrahmanMedhat\BlogsScraper\Models\Post;

    $posts = Post::with(['category','blog','author','tags'])
    ->simplePaginate(15)->toArray();
```
­
---
­

#### Get all blogs with relationships.

```php
    use AbdelrahmanMedhat\BlogsScraper\Models\Blog;

    $blogs = Blog::with(['posts'])
    ->simplePaginate(15)->toArray();
```
­
---
­

#### Get all authors with relationships.
```php
    use AbdelrahmanMedhat\BlogsScraper\Models\Author;

    $authors = Author::with(['posts'])
    ->simplePaginate(15)->toArray();
```
­
---
­

#### Get all categories with relationships.
```php
    use AbdelrahmanMedhat\BlogsScraper\Models\Category;

    $categories = Category::with(['posts'])
    ->simplePaginate(15)->toArray();
```
­
---
­

#### Get all tags with relationships.

```php
    use AbdelrahmanMedhat\BlogsScraper\Models\Tag;

    $tags = Tag::with(['posts'])
    ->simplePaginate(15)->toArray();
```
­
---
­




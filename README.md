
# Blogs Scraper

This package will help you sync posts from popular blogs on the Internet.


## Authors

- [Abdelrahman Medhat](https://github.com/Abdelrahman-Medhat)


## Installation

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
## Parser


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
• Go to this path `app/Parsers` .

• Copy this command and paste it on terminal.

• Replace `blog-name` with blog name you will scrape data from it.
```bash
php artisan create:parser blog-name
```

<?php 

namespace AbdelrahmanMedhat\BlogsScraper;

use Illuminate\Support\ServiceProvider;

class BlogsScraperServiceProvider extends ServiceProvider
{
    public function boot(){
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/config/blogsscraper.php',
            'blogsscraper'
        );
        $this->publishes([
            __DIR__.'/Parsers' => app_path('Parsers'),
            __DIR__.'/Console' => app_path('Console'),
        ]);
    }

    public function register(){

    }
}

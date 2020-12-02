<?php

namespace MichaelBerry\MBImage;

use Illuminate\Support\ServiceProvider;

class MBImageServiceProvider extends ServiceProvider
{
  
  public function boot()
  {
    $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    $this->loadViewsFrom(__DIR__.'/views', 'mbimage');
    $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    $this->mergeConfigFrom(__DIR__.'/config/mbimage.php', 'mbimage');
    $this->publishes([
      __DIR__.'/config/mbimage.php' => config_path('mbimage.php'),
      __DIR__.'/views' => resource_path('views/vendor/mbimage')
    ]);
  }

  public function register()
  {

  }

}

<?php

namespace App\Providers;

use Despark\ImagePurify\ImagePurifierFactory;
use Despark\ImagePurify\Interfaces\ImagePurifierInterface;
use Illuminate\Support\ServiceProvider;

class ImagePurifyServiceProvider extends ServiceProvider
{

    protected $defer = true;

    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton(ImagePurifierInterface::class, function ($app) {
            $options = config('image-purify');
            $logger = $app['log'];

            $factory = new ImagePurifierFactory($options, $logger);

            return $factory->create();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [ImagePurifierInterface::class];
    }
}

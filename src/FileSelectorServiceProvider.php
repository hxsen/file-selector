<?php

namespace Encore\FileSelector;

use Illuminate\Support\ServiceProvider;

class FileSelectorServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FileSelector $extension)
    {
        if (! FileSelector::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'file-selector');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/hxsen/file-selector')],
                'file-selector'
            );
        }

        $this->app->booted(function () {
            FileSelector::routes(__DIR__.'/../routes/web.php');
        });
    }
}
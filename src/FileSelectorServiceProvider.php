<?php

namespace Encore\FileSelector;

use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;
use Encore\Admin\Admin;

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

        // 启动该插件的时候，再加载js和css文件
        Admin::booting(function () {
            // 加载媒体的字段类
            Form::extend('media', FileSelectorField::class);
        });
        // 启动成功之后，再加载js和css文件，如果写在这里的话，没有使用该功能的页面，也会加载样式。所以不再这里写，在静态模板引入
        Admin::booted(function () {
            // 暂时不在这里写
            // Admin::css('/vendor/hxsen/file-selector/file-selector.css');
        });
    }
}

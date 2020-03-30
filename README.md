laravel-admin extension
======
laravel-admin的扩展，选择并使用媒体库的文件
### 安装
```
omposer require hxsen/file-selector
```

### 发布资源
```
php artisan vendor:publish --provider=Encore\FileSelector\FileSelectorServiceProvider
```
### 配置
注册进laravel-admin,在app/Admin/bootstrap.php中添加以下代码：
```
Encore\Admin\Form::extend('media', \Encore\FileSelector\FileSelectorField::class);
```

### 依赖说明(如果已安装，可跳过)
该插件依赖media-manager插件(如果已经安装并配置过该插件，可以忽略)
以下是对media-manager的操作
本扩展共用media-manager的配置，如已发布，可跳过
1. 发布media-manager的文件
    ```$xslt
    php artisan admin:import media-manager
    ```
2. 配置config/admin.php文件的
    ```
    'extensions' => [

        'media-manager' => [
        
            // Select a local disk that you configured in `config/filesystem.php`
            'disk' => 'public'
        ],
    ],
    ```
### 使用方法
在你使用的form组件中，直接使用就行了
1. 简单使用，默认使用的是radio的单选和跟路径的目录，使用实例如下
    ```$xslt
    $form->media('photo', __('Photo'));
    ```
2. 指定多选，如果需要多选图片的话，可以指定type的值。如：
    ```$xslt
    $form->media('photo', __('Photo'))->type('checkbox');
    ```
3. 指定文件目录，如果需要指定文件目录的话，可以指定path的值。如：
    ```$xslt
    $form->media('photo', __('Photo'))->path('article');
    ```

> 提示  
> 本插件不支持多级目录，也不支持目录选择

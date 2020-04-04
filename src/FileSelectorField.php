<?php

namespace Encore\FileSelector;

use Encore\Admin\Form\Field;
use Encore\Admin\Media\MediaManager;

class FileSelectorField extends Field
{
    protected $view = 'file-selector::index';
    protected $media;
    protected $variables;
    protected $path = '/';
    protected $type = 'radio';

    private function getMedia()
    {
        $fullUrl = config('filesystems.disks.'.config('admin.extensions.media-manager.disk').'.url');

        $this->media = new MediaManager($this->path);
        $this->variables = [
            'list'=>$this->media->ls(),
            'basePath'=>parse_url($fullUrl)['path'],
            'path'=>$this->path,
            'type' => $this->type,
        ];
    }
    // 设置查询的目录的路径
    public function path($path='/')
    {
        $this->path = $path;

        return $this;
    }
    /*
     * 设置是否多选
     * 支持radio和checkbox两种
     */

    public function type($type='radio'){
        $this->type = in_array($type, ['radio', 'checkbox']) ? $type : 'radio';
        return $this;
    }

    public function render()
    {
        $this->getMedia();
        return parent::render();
    }
}

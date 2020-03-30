<?php

namespace Encore\FileSelector\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class FileSelectorController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body(view('file-selector::index'));
    }
}
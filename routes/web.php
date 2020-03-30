<?php

use Encore\FileSelector\Http\Controllers\FileSelectorController;

Route::get('file-selector', FileSelectorController::class.'@index');
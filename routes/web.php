<?php

use \Zhxlan\Laradmin\Controller as Laradmin;


Route::get('/laradmin', [Laradmin\IndexController::class, 'index'])->name('laradmin.index');

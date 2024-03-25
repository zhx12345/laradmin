<?php

Route::get('/laradmin', [\Zhxlan\Laradmin\Http\Controllers\IndexController::class, 'index'])->name('laradmin.index');

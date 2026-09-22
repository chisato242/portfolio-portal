<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 公開API(Flutter Webから参照)
| 認証不要・読み取り専用。CORSの許可はconfig/cors.phpで設定してください。
|--------------------------------------------------------------------------
*/
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
Route::get('/tags', [ProjectController::class, 'tags']);

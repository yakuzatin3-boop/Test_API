<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are versioned. Business/domain routes live in
| routes/api/{version}.php so new versions can be introduced safely.
|
*/

Route::prefix('v1')->group(__DIR__.'/api/v1.php');

<?php

use App\Http\Controllers\Api\GeneralApiController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
 */

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->describe('Display an inspiring quote');

Artisan::command('autoprice:change', function () {
    try {
        $controller = resolve(GeneralApiController::class);
        $controller->autoPriceChangeNew();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
    }
})->describe('Automatically change price');
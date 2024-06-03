<?php

use App\Http\Controllers\Api\GeneralApiController;
use App\Http\Controllers\Api\SellerApiController;
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

Artisan::command('settax:hourly', function () {
    try {
        $controller = resolve(GeneralApiController::class);
        $controller->setTaxHourly();
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
    }
})->describe('Set tax hourly');

Artisan::command('auto_sell_delay', function () {
    try {
        sleep(30);
        error_log('Auto sell command called');
        $controller = resolve(GeneralApiController::class);
        $controller->something();

    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
    }
})->describe('Automatically sell products');

Artisan::command('auto_sell', function () {
    try {
        error_log('Auto sell command called');
        $controller = resolve(GeneralApiController::class);
        $controller->something();

    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
    }
})->describe('Automatically sell products');
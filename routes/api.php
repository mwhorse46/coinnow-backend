<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiSellerAuthController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\ChatsApiController;
use App\Http\Controllers\Api\ContestController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\GeneralApiController;
use App\Http\Controllers\Api\SellerApiController;
use App\Http\Controllers\Api\SellerCartApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

Route::get('/something', [GeneralApiController::class, 'something']);
Route::get('/something2', [GeneralApiController::class, 'something2']);
// Route::get('/autoPriceChange', [GeneralApiController::class, 'autoPriceChange']);
Route::get('/autoPriceChange', [GeneralApiController::class, 'autoPriceChangeNew']);
Route::get('/autoPriceTimer', [GeneralApiController::class, 'autoPriceTimer']);
Route::get('/cut', [GeneralApiController::class, 'cut']);
Route::get('/endContest', [ContestController::class, 'endContest']);
// Route::get('/getTrades', [GeneralApiController::class, 'getTrades']);
// Route::middleware(['checkKey'])->group(function () {

Route::middleware(['checkKey'])->group(function () {
    Route::get('testAPI', function () {
        return response()->json(['status' => 1, 'message' => 'Test Done!']);
    });

    Route::controller(GeneralApiController::class)->group(function () {
        Route::get('/getGuide/{type}', 'getGuide');
        Route::get('/getHomePage', 'getHomePage');
        Route::get('/getNewProducts', 'getNewProducts');
        Route::get('/getNewProductsV1', 'getNewProductsV1');
        Route::get('/getTrendingProducts', 'getTrendingProducts');
        Route::get('/getTrendingProductsV1', 'getTrendingProductsV1');
        Route::get('/getDODProducts', 'getDODProducts');
        Route::get('/getBannerImages', 'getBannerImages');
        Route::get('/getButtonImages', 'getButtonImages');
        Route::get('/getCategories', 'getCategories');
        Route::get('/getManufacturers', 'getManufacturers');
        Route::get('/searchProducts', 'searchProducts');
        Route::get('searchOtherSellersProducts', 'searchOtherSellersProducts');
        Route::get('getMarketplaceProducts', 'getMarketplaceProducts');
        Route::get('/productDetail/{id?}', 'productDetails');
        Route::post('/incrementProductView/{id?}', 'incrementProductView');
        Route::get('/getProductByCategory/{id?}', 'getProductByCategory');
        Route::get('/getSecurityQuestions', 'getSecurityQuestions');
        Route::get(
            '/getProductByManufacturer/{id?}',
            'getProductByManufacturer'
        );
        Route::get('/getPages/{id?}', 'getPages');
        // Route::put('/productRandomPrice', 'productRandomPrice');
        Route::get('/news', 'getNews');
        Route::get('/productPrice/{id}', 'productPrices');
        Route::post('/postComment', 'postComment');
        Route::get('/getComments', 'getComments');
        Route::get('/getCoinPrices', 'getCoinPrices');
    });

    Route::group(['prefix' => 'seller'], function () {
        Route::controller(ApiSellerAuthController::class)->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::get('/logout', 'logout');
            Route::post('/getQuestionsByEmail', 'getQuestionsByEmail');
            Route::post('/checkQuestion', 'checkQuestion');
            Route::post('/resetPasswordV1', 'resetPasswordV1');
            Route::post('/getAnswersForQuestions', 'getAnswersForQuestions');
            Route::middleware(['sellerAuth'])->post('/setQuestion', 'setQuestion');
        });

        Route::middleware(['sellerAuth'])->group(function () {
            Route::controller(SellerApiController::class)->group(function () {
                Route::get('getSeller', 'getSellerDetails');
                Route::get('getSellers', 'getSellers');
                Route::post('sendCoins', 'sendCoins');
                Route::get('balanceHistory', 'balanceHistory');
                Route::get('searchProducts', 'searchProducts');
                Route::get('getTrades', 'getTrades');
                Route::post('trade', 'trade');
                Route::post('updateProfile', 'updateProfile');
                Route::post('changePassword', 'changePassword');
                Route::post('updateProduct/{id?}', 'updateProduct');
                Route::post('listProductSale/{id?}', 'listProductSale');
                Route::get('/getHistory', 'getHistory');
                Route::get('/getExpenses', 'getExpenses');
                Route::get('/getEarnings', 'getEarnings');
                Route::post('/payByStripe', 'payByStripe');
                Route::post('/buyCoin', 'buyCoin');
                Route::get('/myClans', 'getMyClans');
                Route::get('/clans', 'getClans');
                Route::get('/joinClans', 'getJoinClans');
                Route::post('/buyClan', 'buyClan');
                Route::post('/clans/{id}', 'updateClan');
                Route::get('/clans/{id}/join', 'joinClan');
                Route::get('/clans/{id}/leave', 'leaveClan');
                Route::get('/clans/{id}/history', 'getClanHistoryById');
                Route::post('/image/upload', 'uploadImage');
                Route::post('/image/delete', 'removeImages');
                Route::get('/image/getMyImages', 'getMyImages');
                Route::get('/image/getImages', 'getImages');
                Route::post('/image/toogleVoteImage', 'toogleVoteImage');
                Route::post('/image/postCommentImage', 'postCommentImage');
                Route::get('/image/getCommentsByImageId/{id}', 'getCommentsByImageId');
                Route::get('/getPriceChangeHistory/{id}', 'getPriceChangeHistory');
            });

            //cart functionality
            Route::controller(SellerCartApiController::class)->group(
                function () {
                    Route::post('/addToCart', 'addToCart');
                    Route::get('/getCart', 'getCart');
                    Route::post('/updateCart', 'updateCart');
                    Route::post('/deleteCart', 'deleteCart');
                    Route::post('/applyCoupon', 'applyCoupon');
                    Route::post('/selectShipping/{id?}', 'selectShipping');
                    Route::post('/placeOrder', 'placeOrder');
                    Route::post('/buyProduct', 'buyProduct');
                    Route::post('/buyProductV1', 'buyProductV1');
                    Route::post('/fightProduct', 'fightProduct');
                    Route::get('/getOrdersList', 'getOrdersList');
                }
            );

            //invest
            Route::controller(ContestController::class)->group(
                function () {
                    Route::post('/invest', 'invest');
                    Route::get('/stars', 'getStars');
                    Route::get('/contest', 'index');
                    Route::get('/investedImages', 'getInvestedImages');
                }
            );

            //chat between sellers
            Route::group(['prefix' => 'chat'], function () {
                Route::controller(ChatsApiController::class)->group(
                    function () {
                        Route::post('/sendMessage', 'sendMessage');
                        Route::get('/users', 'getUsers');
                        Route::get(
                            '/getMessagesByChannel',
                            'getMessagesByChannel'
                        );
                        Route::get(
                            '/getMessagesByReceiver',
                            'getMessagesByReceiver'
                        );
                        Route::get('/getReceivedMessagesCounts', 'getReceivedMessagesCounts');
                    }
                );
            });
        });
    });

    Route::group(['prefix' => 'user'], function () {
        Route::controller(ApiAuthController::class)->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/socialLogin', 'socialLogin');
            Route::post('/socialRegister', 'socialRegister');
            Route::get('/logout', 'logout');
        });

        Route::middleware(['customerAuth'])->group(function () {
            Route::controller(CustomerApiController::class)->group(function () {
                Route::get('getCustomer', 'getCustomerDetails');
                Route::post('updateProfile', 'updateProfile');
                Route::post('addUpdateWishlist', 'addUpdateWishlist');
                Route::get('getWishlist', 'getWishlist');
                Route::post('changePassword', 'changePassword');
                Route::post('changeProfilePicture', 'changeProfilePicture');
                Route::post('/addAddress', 'addAddress');
                Route::post('/editAddress/{id?}', 'editAddress');
                Route::post('/deleteAddress/{id?}', 'deleteAddress');
                Route::post('/addReview', 'addReview');
                Route::get('/getAdress', 'getAdress');
            });

            //cart functionality
            Route::controller(CartApiController::class)->group(function () {
                Route::post('/addToCart', 'addToCart');
                Route::get('/getCart', 'getCart');
                Route::post('/updateCart', 'updateCart');
                Route::post('/deleteCart', 'deleteCart');
                Route::post('/applyCoupon', 'applyCoupon');
                Route::get('/getCheckoutData', 'getCheckoutData');
                Route::post('/selectShipping/{id?}', 'selectShipping');
                Route::post('/placeOrder', 'placeOrder');
                Route::get('/getOrdersList', 'getOrdersList');
            });
        });
    });
});

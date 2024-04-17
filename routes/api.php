<?php

use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\GuideController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\AnnouncementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;
use App\Models\Faq;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth
Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);

Route::group(['middleware' => 'apiAuth'], function () {
    // Guide
    Route::post('/index_guide_pagination', [GuideController::class, 'index_guide_pagination']);
    Route::post('/store_guide', [GuideController::class, 'store_guide']);
    Route::post('/detail_guide', [GuideController::class, 'detail_guide']);

    // Faq
    Route::post('/index_faq_pagination', [FaqController::class, 'index_faq_pagination']);
    Route::post('/store_faq', [FaqController::class, 'store_faq']);
    Route::post('/detail_faq', [FaqController::class, 'detail_faq']);

    // Announcement
    Route::post('/index_announcement_pagination', [AnnouncementController::class, 'index_announcement_pagination']);
    Route::post('/store_announcement', [AnnouncementController::class, 'store_announcement']);
    Route::post('/detail_announcement', [AnnouncementController::class, 'detail_announcement']);

    // Campaign
    Route::post('/index_campaign', [CampaignController::class, 'index_campaign']);
    Route::post('/store_campaign', [CampaignController::class, 'store_campaign']);
    Route::post('/detail_campaign', [CampaignController::class, 'detail_campaign']);
    Route::post('/destroy_campaign', [CampaignController::class, 'destroy_campaign']);
    Route::post('/update_verified_status', [CampaignController::class, 'update_verified_status']);
    Route::post('/get_comment_campaign', [CampaignController::class, 'get_comment_campaign']);
    Route::post('/store_comment_campaign', [CampaignController::class, 'store_comment_campaign']);

    // Dashboard
    Route::post('/index_dashboard', [DashboardController::class, 'index_dashboard']);
    Route::post('/detail_review', [DashboardController::class, 'detail_review']);
});

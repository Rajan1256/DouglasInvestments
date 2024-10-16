<?php

use App\Http\Controllers\Api\SharePointController;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
});

Route::get('user-folder', [SharePointController::class, 'index']);

Route::get('addupdate-company-year-files-cronjob', [SharePointController::class, 'company_cron']);

Route::get('sendmain-cronjob',[SharePointController::class,'sendMailCron']);

/*Route::get('remove-folder', [SharePointController::class, 'remove_folders']);
Route::get('company-year-cron', [SharePointController::class, 'company_year_cron']);

Route::get('company-year-files-cron', [SharePointController::class, 'company_year_file_cron']);

Route::get('getUsersfolder', [SharePointController::class, 'get_user_folder']);*/

Route::get('single-index', [SharePointController::class, 'single_index']);

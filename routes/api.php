<?php
use Weigatherboss\BossLogin\Http\Controllers\Api\BossScanController;

Route::group(['prefix'=>'boss'],function(){
    Route::any('boss_user',BossScanController::class.'@bossUser');
    Route::any('boss_login',BossScanController::class.'@bossLogin');
});
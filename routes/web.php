<?php

use Weigatherboss\BossLogin\Http\Controllers\Admin\BossAdminController;
use Weigatherboss\BossLogin\Http\Controllers\Admin\BossLoginController;
Route::resource('admin_boss', BossAdminController::class);
// 总码登陆逻辑
Route::get('auth/boss_login', BossLoginController::class. '@bossLogin');
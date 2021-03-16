<?php

namespace Weigatherboss\BossLogin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminBossLogin extends Model
{
    public $table = 'admin_boss_login';

    public function be_one_admin()
    {
        $userModel = config('admin.database.users_model');
        $userName = config('admin.database.users_table');
        return $this->hasOne(new $userModel,'id','admin_id');
    }
}

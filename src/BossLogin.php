<?php

namespace Weigatherboss\BossLogin;

use Encore\Admin\Extension;

class BossLogin extends Extension
{
    public $name = 'boss_login';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $migrate = __DIR__.'/../database/migrations';

    public $menu = [
        'title' => '总码管理员',
        'path'  => 'admin_boss',
        'icon'  => 'fa-gears',
    ];

    public function handle(Extension $extension)
    {
        $extension::import();
    }
}
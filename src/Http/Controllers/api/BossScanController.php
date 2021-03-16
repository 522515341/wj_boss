<?php

namespace Weigatherboss\BossLogin\Http\Controllers\Api;

use Weigatherboss\BossLogin\Models\AdminBossLogin;
use Weigatherboss\BossLogin\Models\AdminBossLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Weigatherboss\BossLogin\Http\Controllers\Api\BassController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class BossScanController extends Content
{
    /**
     * 接口验证配置
     * @var array
     */
    public $bass = [
        'app_id' => ['required','string'],
        'timestamp' => 'required|numeric',
        'nonce_str' => 'required|string',
        'sign' => 'required|string',
        'extend' => 'nullable|string',
    ];
    public $validates = [
        // 'status' => [
        //     'code_id' => 'required|exists:admin_scan_log,code_id',
        //     'type' => 'required',
        // ],
        // 'bind' => [
        //     'code_id' => 'required|exists:admin_scan_log,code_id',
        //     'user_token' => 'required|string',
        // ],
        'login' => [
            'code_id' => 'required|exists:admin_boss_log,code_id',
            'token' => 'required|string',
            // 'user_token' => 'required|string',
        ],
        // 'auth' => [
        //     'code_id' => 'required|exists:admin_scan_log,code_id',
        //     'token' => 'required|string',
        //     'user_token' => 'required|string',
        //     'is_login' => 'required',
        // ],
        'bossUser' => [
            // 'code_id' => 'required|exists:admin_boss_log,code_id',
            'user_token' => 'required|string',
        ],
    ];

    /**
     * 验证参数和签名
     * @param Request $request
     * @param $type
     * @return array|bool
     */
    public function checkData($type)
    {
        $this->bass['app_id'][] = Rule::in([config('wj_boss_login_service.user_center.app_id')]);
        $validates = array_merge($this->bass,$this->validates[$type]);
        $validatedData = Validator::make(Request()->all(),$validates);
        
        if ($validatedData->fails()) {
            return wj_boss_login_service_api("500", [], $validatedData->errors()->first());
        }
        $Bass = new BassController();
        if ($Bass->sign(Request()->except('sign'), config('wj_ucenter_login_service.user_center.app_secret')) != Request()->sign) {
            return wj_boss_login_service_api('100005', [], '签名错误');
        }
        return true;
    }
    public function bossUser()
    {
        $this->checkData('bossUser');
        $admin_boss_log = [
            'code_id' => Request()->code_id??0,
            'type' => '3',
            'user_token' => Request()->user_token??'null',
            'status' => 1,
            'scan_status' => 1,
            'data' => Request()->extend??'{}',
        ];
        AdminBossLog::insert($admin_boss_log);
        $admin = AdminBossLogin::get()->map(function($map){
            $token = encrypt(['type' => 'login_token', 'time' => time() ,'id' => $map->id ,'admin_id'=>$map->admin_id ,'username'=>$map->be_one_admin['username']]);
            Redis::setex('boss_'.$map->be_one_admin['username'],60,$token);
            $extend = empty(Request()->extend) ? [] : Request()->extend;
            return [
                'nickname' => $map->be_one_admin['name'],
                'username' => $map->be_one_admin['username'],
                'avatar' => wj_boss_login_service_url($map->be_one_admin['avatar']),
                'token' => $token,
                'extend' => array_merge($extend,['item_url'=>admin_url('auth/boss_login').'?boss='.$token]),
            ];
        });
        $admin = $admin->toArray();
        if(empty($admin)){
            return wj_boss_login_service_api('500',[],'当前项目无可登陆账号');
        }else{
            return wj_boss_login_service_api('200',$admin);
        }
    }

    /**
     * 登陆回调
     * @param Request $request
     * @return array
     */
    public function bossLogin()
    {
        $code_id = Request()->code_id;
        $token = Request()->token;
        $this->checkData('login');
        $scanLog = AdminBossLog::where('code_id', $code_id)->first();
        $scanLog->status = 3;
        $scanLog->result = ['boss_token' => $token];
        $scanLog->save();
        return wj_boss_login_service_api('200', [], '登陆成功');
    }
}
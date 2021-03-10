<?php  
function wj_boss_login_service_api($code='00',$data=array(),$msg="成功"){
    return [
        'code'=>$code,
        'msg'=>$msg,
        'data'=>$data,
    ];
}

if (!function_exists('wj_boss_login_service_url')) {
    /**
     * 图片url处理
     * @param $url
     * @return string
     */
    function wj_boss_login_service_url($url)
    {
        if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $url)) {
            $url = 'http://' . env('QINIU_DOMAINS', env('APP_URL') . '/upload') . '/' . $url;
        }
        return $url;
    }
}

if (!function_exists('get_wj_boss_login_service_version')) {
    function get_wj_boss_login_service_version()
    {
        try{
            if(class_exists(\Encore\Admin\Admin::class) &&
                (new ReflectionClass("\Encore\Admin\Admin"))->hasConstant('VERSION')
            ){
                $version = \Encore\Admin\Admin::VERSION;
                $versionArray = explode('.',$version);
                $intVersion = intval($versionArray[0].$versionArray[1]);
                if($intVersion<= 16){
                    return 1;
                }else if($intVersion<=18){
                    return 2;
                }else{
                    return 3;
                }
            }
        }catch (Exception $e){

        }
        return 0;
    }
}
laravel-admin extension
======

### 安装依赖项:

    composer require weigatherboss/boss_login
     
### 发布资源文件

    php artisan vendor:publish --provider="Weigatherboss\BossLogin\BossLoginServiceProvider"
    
### 执行数据库迁移文件

    php artisan migrate

### 其他
 <!-- 在 config/wj_ucenter_login_service.php 中设置相关数据  -->
 请在env文件中配置以下两个个变量
 WJ_BOSS_LOGIN_SERVICE_APP_ID
 WJ_BOSS_LOGIN_SERVICE_APP_SECRET
 WJ_BOSS_LOGIN_SERVICE_APP_HOST
### 目录设置
 <!-- 目录设置 -->
<div>
    <table border="0">
      <tr>
        <th>Version</th>
        <th>Laravel-Admin Version</th>
      </tr>
      <tr>
        <td>^1.0</td>
        <td>>= 1.6.10</td>
      </tr>
      <tr>
        <td>^2.0</td>
        <td>>= 1.8.1</td>
      </tr>
      <tr>
        <td>^3.0</td>
        <td>all</td>
      </tr>
    </table>
</div> 

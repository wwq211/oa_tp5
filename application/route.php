<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__'   =>[
        'id'    => '\d+',
    ],

    '/'             => 'index',

    '[login]'       => [
        '__miss__'  => 'index/Login/index',
        'check'     => 'index/Login/check',
    ],

    '[flow]'        => [
        'apply/1'   => ['index/Leave/apply'],
        'apply/2'   => ['index/Flow/apply'],
        'apply/2'   => ['index/Flow/apply'],
        '__miss__'  => 'index/Flow/index',
    ],

    '[leave]'       => [
        'confirm'   => ['index/Leave/confirm'],
        'submit'    => ['index/Leave/submit']
    ],

    '[menu]'        => [
        'switcher'  => ['index/SysMenu/switcher'],
        'create'    => ['index/SysMenu/create'],
        'save'      => ['index/SysMenu/save'],
        'edit/id/:id' => ['index/SysMenu/edit'],
        'del'       => ['index/SysMenu/del'],
        'destroy'       => ['index/SysMenu/destroy'],
        '__miss__'  => ['index/SysMenu/index'],
    ],
    '[user]'        => [
        'info'      => ['index/User/info'],
        'save'      => ['index/User/save'],
        '__miss__'  => ['index/User/index'],
    ],

];

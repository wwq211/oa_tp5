<?php
namespace app\index\model;
use app\index\model\Common;
use think\model\Relation;

class User extends Common
{
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $insert = ['password' => 'e10adc3949ba59abbe56e057f20f883e'];

    protected $type = [
        'entry_time' => 'timestamp',
        'create_time' => 'timestamp',
    ];

    public function roles()
    {
        return $this->belongsToMany('Role', 'role_user');
    }

    public function getUserList()
    {
        $obj_list = $this->field('id, name, duty_id, project_id')->order('create_time asc')->select();
        $list = [];
        foreach ($obj_list as $value) {
            $list[] = $value->toArray();
        }
        $datas = [];
        foreach ($list as $user) {
            $user_info = [
                'id' => $user['id'],
                'name' => $user['name']
            ];
            $datas[$user['project_id']][$user['duty_id']][] = $user_info;
        }
        return $datas;
    }
}
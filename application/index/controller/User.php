<?php
namespace App\index\controller;
use app\index\controller\Base;
use think\Loader;

class User extends Base
{
    public function index()
    {
        $datas = model('User')->getUserList();
        $conf_list = $this->getConfList();
        return $this->fetch('index',['datas'=>$datas, 'conf_list'=>$conf_list]);
    }

    public function info()
    {
        $info = model('User')->field('id, account, create_time, duty_id, email, entry_time, name, position_id, project_id, status, tel')->where(['id'=>input('get.id')])->find();
        $data = $info->toArray();
        foreach ($info->roles as $role) {
            $role_list[] = $role['id'];
        }
        $data['role_id'] = $role_list;
        return json($data);
    }

    //配置列表
    protected function getConfList(){

        $project_list   = model('project')->where(['status'=>1])->column('name','id');
        $position_list  = model('position')->where(['status'=>1])->column('name','id');
        $duty_list      = model('duty')->where(['status'=>1])->column('name','id');
        $role_list      = model('role')->where(['status'=>1])->column('name','id');

        $conf_list = [
            'project_list'  => $project_list,
            'position_list' => $position_list,
            'duty_list'     => $duty_list,
            'role_list'     => $role_list,
        ];

        return $conf_list;
    }

    //插入新数据
    protected function _insert($name, $data)
    {
        $model = model($name);
        //保存对象
        $result = $model->roles()->fetchSql(true)->save($data);
        die;
/*        foreach ($data['role_id'] as $role_id) {
            $role[] = model('role')->get($role_id);
        }
        $model->roles()->saveAll($role);*/
        if (false !== $result) {
            $this -> success('新增成功!');
        }else {
            $this -> error('新增失败!');
        }
    }

    //更新数据
    protected function _update($name = null, $data)
    {   
        $model = model($name);
        if (!isset($data['id'])) {
            $this -> error('编辑失败-0001!');
        }
        $id = $data['id'];
        //保存对象
        $result = $model->allowField(true)->save($data,['id' => $id]);
        $model->roles()->detach(1);
        die;
        $model->roles()->saveAll($data['role_id']);
        if (false !== $result) {
            //成功提示
            $this -> success('编辑成功!');
        } else {
            //错误提示
            $this -> error('编辑失败-0002!');
        }
    }

}
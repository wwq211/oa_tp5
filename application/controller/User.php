<?php
namespace app\controller;
use app\controller\Base;

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
		$role_list = [];
        foreach ($info->roles as $role) {
            $role_list[] = $role['id'];
        }
        $data['role_id'] = $role_list;
        return json($data);
    }

    //配置列表
    protected function getConfList(){

        $project_list   = model('Project')->where(['status'=>1])->column('name','id');
        $position_list  = model('Position')->where(['status'=>1])->column('name','id');
        $duty_list      = model('Duty')->where(['status'=>1])->column('name','id');
        $role_list      = model('Role')->where(['status'=>1])->column('name','id');

        $conf_list = [
            'project_list'  => $project_list,
            'position_list' => $position_list,
            'duty_list'     => $duty_list,
            'role_list'     => $role_list,
        ];

        return $conf_list;
    }

    //插入新数据
    protected function _insert($name, $data, $field = true)
    {
        $User = model('User');
        //保存对象
        $roles = $data['roles'];
		//插入用户表数据
		$result = $User->allowField($field)->save($data);
		//插入用户权限中间表数据
		$user = $User->get($User->id);
		$user->roles()->saveAll($roles);
        if (false !== $result) {
            $this -> success('新增成功!');
        }else {
            $this -> error('新增失败!');
        }
    }

    //更新数据
    protected function _update($name = null, $data, $field = true)
    {
        $User = model('User');
        if (!isset($data['id'])) {
            $this -> error('编辑失败-0001!');
        }
        $id = $data['id'];
        //更新用户表数据
        $result = $User->allowField($field)->save($data,['id' => $id]);
		//更新用户权限中间表数据
		$user = $User->get($id);
		foreach ($User->roles as $role) {
            $roles[] = $role['id'];
        }
        !empty($roles) && $User->roles()->detach($roles);
        $user->roles()->saveAll($data['roles']);
        if (false !== $result) {
            //成功提示
            $this -> success('编辑成功!');
        } else {
            //错误提示
            $this -> error('编辑失败-0002!');
        }
    }

}

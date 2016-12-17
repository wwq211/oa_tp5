<?php
namespace App\index\controller;
use app\index\controller\Base;

class Menu extends Base
{
    public function index()
    {

        $datas = model('SysMenu')->getMenuList(1);
        $this -> assign('datas', $datas);
        return $this->fetch('index',['datas'=>$datas]);
    }

    public function create()
    {
        $data = input('post.');
        if (empty($data)) {
            $data = [
                'pid'     => 0,
                'name'    => '',
                'title'   => '',
                'icon'    => '',
                'sort'    => 99,
                'status'  => 1,
                'is_show' => 1,
            ];
        }
        $modules = model('SysMenu')->getModusList();
        return $this->fetch('create',['data'=>$data, 'modules'=>$modules]);
    }

    public function edit($id)
    {
        $data = model('SysMenu')->where(['id'=>$id])->find();
        $modules = model('SysMenu')->getModusList();
        return $this->fetch('edit',['data'=>$data, 'modules'=>$modules]);
    }

}
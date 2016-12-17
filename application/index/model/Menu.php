<?php
namespace app\index\model;
use app\index\model\Common;

class Menu extends Common
{
    public function getMenuList($type = 0)
    {
        if ($type == 1) {
            $obj_list = $this->order('sort asc')->select();
            foreach ($obj_list as $value) {
                $list[] = $value->toArray();
            }
        }else {
            $where = ['status'=>1];
            $obj_list = $this->field('id,pid,name,sort,is_show,icon')->order('sort asc')->where($where)->select();
            foreach ($obj_list as $value) {
                $list[] = $value->toArray();
            }
        }
        $list = [];
        $datas = list_to_tree($list, 0, 'id', 'pid', 'menus');
        return $datas;
    }

    public function getModusList()
    {
        $where['pid'] = 0;
        $list = $this->where($where)->column('title','id');
        return $list;
    }

}
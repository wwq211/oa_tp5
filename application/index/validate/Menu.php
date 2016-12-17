<?php 
namespace app\index\validate;
use think\Validate;

class SysMenu extends Validate
{
    protected $rule = [
        'title' => 'require',
        'name' => 'require|checkName:data',
        'pid' => 'require',
        'sort' => 'require',
        'status' => 'require',
        'is_show' => 'require'
    ];

    protected $message = [
        'title.require' => '菜单名称不能为空!',
        'name.require'  =>  '控制器名称不能为空!',
        'pid.require'  =>  '必须选择模块名称!',
        'title.require'  =>  '菜单名称不能为空!',
        'sort.require'  =>  '菜单排序不能为空!',
        'status.require'  =>  '必须选择状态!',
        'is_show.require'  =>  '必须选择显示!',
    ];

    protected $scene = [
        'add' =>  ['name', 'pid', 'sort', 'status', 'is_show']
    ]; 

    // 自定义验证规则
    protected function checkName($value,$rule,$data)
    {
        $map['name'] = $data['name'];
        isset($data['id']) && $map['id'] = ['neq', $data['id']];
        $count = model('SysMenu') -> where($map) ->count();
        
        if ($count) {
            return '已经存在的控制器';
        }else {
            return true;
        }
    }
}
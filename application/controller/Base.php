<?php
namespace app\controller;
use think\Controller;
use think\Loader;

class Base extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$Auth = new \util\Auth();
		$user = $Auth::isLogin();
		if($user){
            if(!$Auth::check()){
                return $this->error("您没有此权限！");
            }
        }else{
            return $this->error("您还没有登录！",url("/login"));
        }
	}

	public function logout()
	{
		$Auth = new \util\Auth();
		$Auth::logout();
		return $this->success("您已退出登录！",url("/login"));
	}

    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
	{
        $this->assign('menus', $this->menus());
        $this->assign('user_info', $this->getUserInfo());
        return $this->view->fetch($template, $vars, $replace, $config);
    }

    public function menus()
    {
        $menus = $datas = model('Menu')->getMenuList();
		foreach ($menus as &$module) {
			$module['active'] = 0;
			$module['url'] = 'javascript:;';
			foreach ($module['menus'] as &$menu) {
				$menu['active'] = 0;
				$menu['url'] = '/' . $menu['controller'] . '/' . $menu['action'];
			}
		}
        return $menus;
    }

    public function getUserInfo()
    {
        $info = [
            'username' => get_user_name(),
            'duty_name' => get_duty_name(),
            'project_name' => get_project_name(),
            'position_name' => get_position_name(),
        ];

        return $info;
    }

    //改变状态操作
    public function switcher()
    {
        $request = request();
        $controller = $request->controller();
        $this -> _switcher($controller);
    }

    //改变状态
    public function _switcher($name = null)
    {
        $model = model($name);
        $data = [
            (string)input('post.name') => input('post.value'),
        ];
        $result = $model -> save($data, ['id'=>input('post.id')]);
        if (false !== $result) {
            //成功提示
            $this -> success('操作成功!');
        } else {
            //错误提示
            $this -> error('操作失败!');
        }
    }

    //保存操作
    public function save()
    {
        $request = request();
        $controller = $request->controller();
        $this -> _save($controller);
    }

    public function _save($name = null, $filed = true)
    {
        $data = input('post.');
        $validate = Loader::validate($name);
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        switch ($data['mode']) {
            case 'add':
                unset($data['mode']);
                $this -> _insert($name, $data, $filed);
                break;

            case 'edit':
                unset($data['mode']);
                $this -> _update($name, $data, $filed);
                break;

            default:
               $this -> error("非法操作");
        }
    }

    //插入新数据
    protected function _insert($name = null, $data, $filed)
    {
        $model = model($name);
        //保存对象
        $result = $model->allowField($filed)->save($data);
        if (false !== $result) {
            $this -> success('新增成功!', session('url'));
        }else {
            $this -> error('新增失败!');
        }
    }

    //更新数据
    protected function _update($name = null, $data, $filed)
    {
        $model = model($name);
        if (!isset($data['id'])) {
            $this -> error('编辑失败-0001!');
        }
        $id = $data['id'];
        //保存对象
        $result = $model->allowField($filed)->save($data,['id' => $id]);
        if (false !== $result) {
            //成功提示
            $this -> success('编辑成功!', session('url'));
        } else {
            //错误提示
            $this -> error('编辑失败-0002!');
        }
    }

    //删除标记操作
    public function del()
    {
        $request = request();
        $controller = $request->controller();
        $this -> _del($controller);
    }

    //删除标记
    protected function _del($name = null) {
        $model = model($name);
        $result = $model->save(['is_del'=>1], ['id'=>input('post.id')]);
        if (false !== $result) {
            $this -> success('删除成功!', session('url'));
            //成功提示
        } else {
            $this -> error('删除失败!');
            //错误提示
        }
    }

    //删除数据操作
    public function destroy()
    {
        $request = request();
        $controller = $request->controller();
        $this -> _destroy($controller);
    }

    //删除标记
    protected function _destroy($name = null) {
        $model = model($name);
        $result = $model->destroy(['id'=>input('post.id')]);
        if (false !== $result) {
            $this -> success('删除成功!', session('url'));
            //成功提示
        } else {
            $this -> error('删除失败!');
            //错误提示
        }
    }
}

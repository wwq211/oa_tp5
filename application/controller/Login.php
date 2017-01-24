<?php
namespace app\controller;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return view();
    }

    public function check()
    {
        $account = input('post.account');
        $password = input('post.password');
        $auth = new \util\Auth();
        $user = $auth::loginByAccount($account, $password);

        if(is_array($user) && !empty($user['id'])){
			foreach ($user['roles'] as $role) {
				$roles[] = $role['id'];
			}
            $user_auth = [
                    'uid' => $user['id'],
                    'account' => $account,
                    'roles' => $roles,
					'project_id' => $user['project_id'],
                    'duty_id' => $user['duty_id'],
                    'position_id' => $user['position_id'],

            ];
            session('user_auth', $user_auth);
            $this->success('登陆成功!', url('/'));
        }else{
            //登录失败
            switch ($user){
                case -1: $error = '帐号不存在或被禁用！'; break;
                case -2: $error = '帐号已被锁定,请稍后再试！'; break;
                case -3: $error = '密码错误!'; break;
                case -4: $error = '帐号未分配权限角色！'; break;
                case -6: $error = '帐号角色权限不存在或被禁用！'; break;
                case -7: $error = '自动登录失败,请输入帐号密码！'; break;
                default: $error = '系统错误,请与相关技术联系！'; break;
            }
            return $this->error($error);
        }
    }
}

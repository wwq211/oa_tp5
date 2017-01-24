<?php
namespace app\controller;
use app\controller\Flow;
use think\Loader;

class Leave extends Flow
{
	public function getParam()
    {
		return ['leave_type'=>[1=>'事假',2=>'病假',4=>'补休']];
    }

    public function updateConfirm()
    {
        $leave_day = input('leave_day/d', 0);
        $data = [
            'leave_day' => $leave_day
        ];
        $FlowConfig = model('FlowConfig');
        $flow = $FlowConfig->getFlow(1, $data);
		$flow_name = implode(' -> ', $flow['flow_name']);
        return $flow_name;
    }

    public function defaultData()
    {
		return [
			'start_time' => '',
			'end_time' => '',
			'describe' => '',
			'leave_type' => '',
			'leave_day' => ''
		];
    }

	public function data($flow_id)
	{
		return model('Leave')->get(['flow_id' => $flow_id]);
	}

    public function getConfig()
    {
        $FlowConfig = model('FlowConfig');
        $config = $FlowConfig->getConfig(1);
        return $config;
    }

    public function _save($name = 'Leave', $filed=false)
    {
		$data = input('post.');
        $validate = Loader::validate($name);
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        if ($data['leave_type'] == 4 && !input('?post.overtime')) {
            $this->error("加班时间必须");
        }
		switch ($data['mode']) {
            case 'add':
                unset($data['mode']);
                $this -> _insert($name, $data);
                break;

            case 'edit':
                unset($data['mode']);
                $this -> _update($name, $data);
                break;

            default:
               $this -> error("非法操作");
        }
    }

	protected function _insert($name = null, $data, $filed=false)
	{
        $flow_id = $this->flowAdd($data, 1);
        $data_leave = [
            'flow_id' => $flow_id,
            'user_id' => get_user_id(),
            'start_time' => strtotime($data['start_time']),
            'end_time' => strtotime($data['end_time']),
            'status' => 0,
            'describe' => $data['describe'],
            'leave_type' => $data['leave_type'],
            'leave_day' => $data['leave_day']
        ];
        input('?post.overtime') && $data_leave['overtime'] = $data['overtime'];
        $result = model($name)->save($data_leave);
        if ($result) {
            $this->success("提交成功!", '/flow/submit');
        } else {
            $this->error("提交失败-0002!请重试或联系管理员");
        }
	}

	//更新数据
    protected function _update($name = null, $data, $filed=false)
    {
        $model = model($name);
        if (!isset($data['id'])) {
            $this -> error('修改失败-0001!');
        }
		$this->flowUpdate($data);
		$data_leave = [
            'user_id' => get_user_id(),
            'start_time' => strtotime($data['start_time']),
            'end_time' => strtotime($data['end_time']),
            'status' => 0,
            'describe' => $data['describe'],
            'leave_type' => $data['leave_type'],
            'leave_day' => $data['leave_day']
        ];
        input('?post.overtime') && $data_leave['overtime'] = $data['overtime'];
        //保存对象
        $result = $model->save($data_leave,['flow_id' => $data['id']]);
        if (false !== $result) {
            //成功提示
            $this -> success('修改成功!', '/flow/submit');
        } else {
            //错误提示
            $this -> error('修改失败-0002!');
        }
    }
}

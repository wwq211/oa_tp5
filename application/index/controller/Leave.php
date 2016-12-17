<?php
namespace app\index\controller;
use app\index\controller\Flow;
use think\Loader;

class Leave extends Flow
{
    public function confirm()
    {
        $leave_day = input('leave_day', 0);
        $data = [
            'leave_day' => $leave_day
        ];
        $FlowConfig = model('FlowConfig');
        $flow = $FlowConfig->getFlow(1, get_project_id(), $data);
        return view('flow/confirm', ['flow' => $flow]);
    }

    public function getDefaultData()
    {

    }

    public function config()
    {
        $FlowConfig = model('FlowConfig');
        $config = $FlowConfig->getConfig(1);
        return $config;
    }

    public function submit()
    {   
        $data = input('post.');
        $validate = Loader::validate('Leave');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        if ($data['leave_type'] == 4 && !input('?post.overtime')) {
            $this->error("加班时间必须");
        }
        $FlowConfig = model('FlowConfig');
        $flow = $FlowConfig->getFlow(1, get_position_id(), $data);
        $step = $FlowConfig->getStep(0, $flow['confirm_list']);
        $data_flow = [
            'title' => $data['title'],
            'type' => 1,
            'user_id' => get_user_id(),
            'create_time' => time(),
            'step' => $step,
            'is_edit' => 1
        ];
        $flow = model('Flow');
        $flow->data($data_flow);
        $flow->save();
        $flow_id = $flow->id;
        if (!$flow_id) {
            $this->error("提交失败-0001!请重试或联系管理员");
        }

        $data_leave = [
            'flow_id' => $flow_id,
            'user_id' => get_user_id(),
            'start_time' => strtotime($data['start_time']),
            'end_time' => strtotime($data['end_time']),
            'status' => 0,
            'describe' => $data['describe'],
            'leave_type' => $data['leave_type'],
            'create_time' => time(),
            'leave_day' => $data['leave_day'],
        ];
        input('?post.overtime') && $data_leave = $data['overtime'];
        $result = model('Leave')->save($data_leave);
        if ($result) {
            $this->success("提交成功!");
        } else {
            $this->error("提交失败-0002!请重试或联系管理员");
        }
    }
}

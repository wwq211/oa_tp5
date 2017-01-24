<?php
namespace app\controller;
use app\controller\Base;

class Flow extends Base
{
	private static $status = [0 => '审核中', 1 => '通过', 2 => '拒绝', 3 => '回退'];

	//流程申请主页面
    public function index()
    {
        $FlowConfig = model('FlowConfig');
        $tag_list = $FlowConfig->getFlowList(1,['id','name','classify','controller']);
        return $this->fetch('index',['tag_list'=>$tag_list]);
    }

	//流程申请管理页面
	public function manage()
	{
		$FlowConfig = model('FlowConfig');
        $tag_list = $FlowConfig->getFlowList(0,['id','name','classify','controller', 'status']);
        return $this->fetch('manage',['tag_list'=>$tag_list]);
	}

	//流程组管理页面
	public function classify($id = null)
	{
		$FlowClassify = model('FlowClassify');
		$data=[
			'name' => '',
			'status' => 1,
			'is_del' =>0
		];
		$mode = 'add';
		if ($id) {
			$data = $FlowClassify->get($id);
			$mode = 'edit';
		}

		return $this->fetch('classify', ['data' => $data, 'mode'=>$mode]);
	}

	//流程组保存操作
	public function classifySave()
	{
		$name = 'FlowClassify';
		$this->_save($name);
	}

	//流程配置页面
	public function config($id = null, $cid =null)
	{
		$FlowConfig = model('FlowConfig');
		$FlowAudit = model('FlowAudit');
		$data=[
			'name' => '',
			'status' => 1,
			'classify' => $cid,
			'report_user' => '',
			'controller' => ''
		];
		$mode = 'add';
		if ($id) {
			$data = $FlowConfig->get($id);
			$audits = $FlowAudit->where(['type' => $id, 'is_del'=>0])->column('name, status', 'id');
			$data['audits'] = $audits;
			$mode = 'edit';
		}

		return $this->fetch('config', ['data' => $data, 'mode'=>$mode]);
	}

	//流程配置保存操作
	public function configSave()
	{
		set_url('/flow/manage');
		$name = 'FlowConfig';
		$this->_save($name);
	}

	//流程走向配置页面
	public function audit($id = null, $tid =null)
	{
		$FlowAudit = model('FlowAudit');
		$FlowAuditConfig = model('FlowAuditConfig');
		$data=[
			'name' => '',
			'audit_conf' => '',
			'type' => $tid,
			'status' => 1,
			'project_id' => [],
			'duty_id' => [],
			'position_id' => []
		];
		$mode = 'add';
		if ($id) {
			$data = $FlowAudit->get($id);
			//获取相关配置
			$data['project_id'] = $FlowAuditConfig->where(['audit_id' => $id])->column('project_id');
			$data['duty_id'] = $FlowAuditConfig->where(['audit_id' => $id])->column('duty_id');
			$data['position_id'] = $FlowAuditConfig->where(['audit_id' => $id])->column('position_id');
			$mode = 'edit';
		}
		$conf_list = model('Common')->getConfList();
		return $this->fetch('audit', ['data' => $data, 'mode'=>$mode, 'conf_list'=>$conf_list]);
	}

	//流程走向保存操作
	public function auditSave()
	{
		$id = input('post.id');
		$type = input('post.type');
		set_url('/flow/config/id/'.$type);
		$FlowAuditConfig = model('FlowAuditConfig');
		if (!$id) {
			$name = 'FlowAudit';
			$this->_save($name, true);
			exit;
		}
		$project_id = input('post.project_id/a');
		$duty_id = input('post.duty_id/a');
		$position_id = input('post.position_id/a');
		if (is_array($project_id) && count($project_id)>0 && is_array($duty_id) && count($duty_id)>0 && is_array($position_id) && count($position_id)>0) {
			$FlowAuditConfig->where(['audit_id'=>$id, 'type'=>$type])->delete();
			for ($i=0; $i < count($project_id); $i++) {
				for ($d=0; $d < count($duty_id); $d++) {
					for ($p=0; $p < count($position_id); $p++) {
						$data[] = [
							'project_id' => $project_id[$i],
							'duty_id' => $duty_id[$d],
							'position_id' => $position_id[$p],
							'type' => $type,
							'audit_id' => $id
						];
					}
				}
			}
			if (!empty($data)) {
				if($FlowAuditConfig->saveAll($data)){
					$name = 'FlowAudit';
					$this->_save($name, true);
				}else{
					$this->error('适用配置保存失败');
				}
			}
		}else {
			$this->error('适用配置不能为空!');
		}
	}

	//流程走向删除标记操作
	public function auditDel()
	{
		$id = input('post.id');
		$type = input('post.type');
		set_url('/flow/config/id/'.$type);
		$FlowAuditConfig = model('FlowAuditConfig');
		$FlowAuditConfig->where(['audit_id'=>$id, 'type'=>$type])->delete();
		$name = 'FlowAudit';
		$this->_del($name, true);
	}

	//流程申请(公用方法)
    public function apply()
    {
        $config = $this->getConfig();
		$config['param'] = $this->getParam();
        $data = [
            'title' => $config['name'].date('YmdHis').get_user_name(),
        ];
        $default_data = $this->defaultData();
        $default_data && $data += $default_data;
		$show = [
			'log' => 0,
			'confirm' => 0
		];
		$confirm = $this->getConfirm();
        return $this->fetch('apply', ['data'=>$data, 'config'=>$config, 'mode'=>'add', 'show'=>$show, 'confirm'=>$confirm]);
    }

	//已提交页面
	public function submit()
	{
		$Flow = model('Flow');
		$where = ['user_id' => get_user_id(), 'is_del' => 0];
		$datas = $Flow->where($where)->field('id, title, type, step, status, create_time')->order('create_time desc')->select();
		$list = [];
		foreach ($datas as $data) {
			$data = $this->getFlowData($data, 'read');
			$list[] = $data;
		}
		return $this->fetch('submit', ['list' => $list]);
	}

	//待审核页面
	public function confirm()
	{
		$FlowLog = model('FlowLog');
		$Flow = model('Flow');
		$log_list = $FlowLog->where(['user_id' => get_user_id(), 'is_del' => 0])->where('result is null')->column('flow_id');
		$list = [];
		if ($log_list) {
			$where = ['id' => ['in', $log_list], 'is_del' => 0];
			$datas = $Flow->where($where)->field('id, title, type, step, status,create_time')->order('create_time desc')->select();
			foreach ($datas as $data) {
				$data = $this->getFlowData($data, 'confirmRead');
				$list[] = $data;
			}
		}
		return $this->fetch('confirm', ['list' => $list]);
	}

	protected function getFlowData($flow_data, $action){
		$FlowConfig = model('FlowConfig');
		$info = $FlowConfig->where(['id' => $flow_data['type']])->field('name,controller')->find();
		$Model = model($info['controller']);
		$data = $Model::get(['flow_id' => $flow_data['id']]);
		$flow = $FlowConfig->getFlow($flow_data['type'], $data->toArray(), $flow_data['id']);
		$flow_data['step'] = $flow['flow_name'][$flow_data['step']];
		$flow_data['status'] = self::$status[$flow_data['status']];
		$flow_data['type'] = $info['name'];
		$flow_data['url'] = url('/' . $info['controller'] . '/' . $action,['id' => $flow_data['id']]);

		return $flow_data;
	}

	//流程查看页面
	public function read($id)
	{
		$Flow = model('Flow');
		$config = $this->getConfig();
		$config['param'] = $this->getParam();
		$flow = $Flow->get($id);
        $data = [
			'id' => $id,
            'title' => $flow['title']
        ];
        $flow_data = $this->data($id);
        $flow_data && $data += $flow_data->toArray();
		$show = ['log' => 1, 'confirm' => 0];
		$mode = $flow['is_edit'] ? 'edit' : 'read';
		$confirm = $this->getConfirm($id);
		$flow_log = $this->getFlowLog($id);

        return $this->fetch('apply', ['data'=>$data, 'config'=>$config, 'mode'=>$mode, 'show'=>$show, 'confirm'=>$confirm, 'flow_log'=>$flow_log]);
	}

	//流程审核查看页面
	public function confirmRead($id)
	{
		$Flow = model('Flow');
		$config = $this->getConfig();
		$config['param'] = $this->getParam();
		$flow_info = $Flow->where(['id' => $id])->field('step, title')->find();
        $data = [
			'id' => $id,
			'step' => $flow_info['step'],
            'title' => $flow_info['title']
        ];
        $flow_data = $this->data($id);
        $flow_data && $data += $flow_data->toArray();
		$show = ['log' => 1, 'confirm' => 1];
		$mode = 'confirm';
		$confirm = $this->getConfirm($id);
		$confirm_list = explode(' -> ', $confirm);
		$flow_log = $this->getFlowLog($id);

        return $this->fetch('apply', ['data'=>$data, 'config'=>$config, 'mode'=>$mode, 'show'=>$show, 'confirm'=>$confirm, 'flow_log'=>$flow_log, 'confirm_list'=>$confirm_list]);
	}

	//获取审核日志方法
	public function getFlowLog($id)
	{
		$where =[
			'flow_id' => $id,
			'is_del' => 0,
			'result' => ['exp', 'is not null']
		];
		$data = db('flow_log')->where($where)->field('user_id, step, result, comment, update_time')->order('create_time asc')->select();
		return $data;
	}

	//获取显示流程信息
	public function getConfirm($id = null)
    {
		$data =[];
		$FlowConfig = model('FlowConfig');
		$flow_controller = strtolower(request()->controller());
		$type = $FlowConfig->where(['controller' => ['like', $flow_controller]])->value('id');
		if ($id) {
			$controller = $FlowConfig->where(['id' => $type])->value('controller');
			$Model = model($flow_controller);
			$data = $Model::get(['flow_id' => $id]);
			$data = $data->toArray();
		}
        $flow = $FlowConfig->getFlow($type, $data, $id);
		$flow_name = implode(' -> ', $flow['flow_name']);
        return $flow_name;
    }

	//流程新增操作
	public function flowAdd($data, $type)
	{
        $data_flow = [
            'title' => $data['title'],
            'type' => $type,
            'user_id' => get_user_id(),
            'step' => 0,
			'status' => 0,
			'is_del' => 0,
            'is_edit' => 1
        ];
        $Flow = model('Flow');
        $Flow->data($data_flow);
        $Flow->save();
        $flow_id = $Flow->id;
		$this->nextStep($flow_id);
        if (!$flow_id) {
            $this->error("提交失败-0001!请重试或联系管理员");
        }else {
        	return $flow_id;
        }
	}

	//流程更新操作
	public function flowUpdate($data)
	{
		$this->delLog($data['id']);
		$this->nextStep($data['id']);
	}

	//审核下一步操作
	public function nextStep($flow_id)
	{
		$FlowConfig = model('FlowConfig');
		$Flow = model('Flow');
		$flow_data = $Flow->get($flow_id);
		$flow_controller = $FlowConfig->where(['id'=>$flow_data['type']])->value('controller');
		$model = model(ucfirst($flow_controller));
		$model_data = $model->where(['flow_id'=>$flow_id])->find();
		$data = $model_data->toArray();
		$cur_step = $flow_data['step'];
		$flow = $FlowConfig->getFlow($flow_data['type'], $data, $flow_data['id']);
        $step = $FlowConfig->getStep($cur_step, $flow['confirm_list']);
		if (is_array($step) && $step[0] == 'last') {
			//更新流程步骤
			$flow = $Flow->get($flow_id);
			$flow->step = $step[1]+1;
			$flow->status = 1;
			$flow->is_edit = 0;
			$flow->save();
		}else{
			$user_id = $flow['confirm_list'][$step]['id'];
			$step = $step+1;
			$data_log = [
				'flow_id' => $flow_id,
				'user_id' => $user_id,
				'step' => $step,
				'is_del' => 0
			];
			$FlowLog = model('FlowLog');
			$FlowLog->data($data_log);
			$FlowLog->save();

			//更新流程步骤
			$flow = $Flow->get($flow_id);
			$flow->step = $step;
			$flow->save();
		}
	}

	//审核日志标记位操作
	public function delLog($flow_id)
	{
		//更新审核日志表
		$FlowLog = model('FlowLog');
		$where = [
			'flow_id' => $flow_id,
			'result' => ['exp', 'is null']
		];
		$FlowLog->where($where)->update(['is_del' => 1]);

		//更新流程表
		$Flow = model('Flow');
		$Flow->where(['id'=>$flow_id])->update(['step' => 0, 'status'=> 0]);
	}

	//审核流程同意操作
	public function agree()
	{
		$id = input('post.id');
		$comment = input('post.comment');
		if (!$id) {
			$this->error('缺少参数(id)！');
		}
		$Flow = model('Flow');
		$where = ['flow_id' => $id, 'user_id'=>get_user_id()];
		$FlowLog = model('FlowLog')->where($where)->where('result is null')->find();
		$FlowLog->comment = $comment;
		$FlowLog->result = 1;
		$result = $FlowLog->save();
		$Flow->update(['id' => $id, 'is_edit' => 0]);
		$this->nextStep($id);
		if (false !== $result) {
			$this->success('审核成功!', '/flow/confirm');
		}else {
            $this->error('审核失败!');
        }
	}

	//审核流程拒绝操作
	public function reject()
	{
		$id = input('post.id');
		$comment = input('post.comment');
		if (!$id) {
			$this->error('缺少参数(id)！');
		}
		$Flow = model('Flow');
		$where = ['flow_id' => $id, 'user_id'=>get_user_id()];
		$FlowLog = model('FlowLog')->where($where)->where('result is null')->find();
		$FlowLog->comment = $comment;
		$FlowLog->result = 0;
		$result = $FlowLog->save();
		$Flow->update(['id' => $id, 'is_edit' => 0, 'status'=>2]);
		if (false !== $result) {
			$this->success('审核成功!', '/flow/confirm');
		}else {
            $this->error('审核失败!');
        }
	}

	//审核流程回退操作
	public function reconfirm()
	{
		$id = input('post.id');
		$restep = input('post.restep');
		$comment = input('post.comment');
		if (!$id) {
			$this->error('缺少参数(id)！');
		}
		$Flow = model('Flow');
		$where = ['flow_id'=>$id, 'user_id'=>get_user_id(), 'is_del'=>0];
		$FlowLog = model('FlowLog')->where($where)->where('result is null')->find();
		$FlowLog->comment = $comment;
		$FlowLog->result = 3;
		$result = $FlowLog->save();
		$data = ['id' => $id, 'status'=>3, 'step'=>$restep];
		$restep === 0 && $data['is_edit'] == 1;
		$Flow->update($data);
		if (false !== $result) {
			$this->success('审核成功!', '/flow/confirm');
		}else {
            $this->error('审核失败!');
        }
	}
}

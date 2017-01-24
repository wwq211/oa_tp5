<?php
namespace app\model;
use app\model\Common;
use app\model\FlowClassify;
use app\model\FlowAuditConfig;
use app\model\FlowAudit;
use app\model\Flow;

class FlowConfig extends Common
{
    /**
     * 获取流程列表
     * @param  array  $type  查询类型 0=所有 , 1=开启状态
     * @param  array  $field  查询字段
     * @return array  $result 流程列表
     */
    public function getFlowList($type = 0, $field = array())
    {
		$where = [];
		$type && $where = ['status' => 1];
		$classify = FlowClassify::where($where)->select();
		$list = [];
		foreach ($classify as $value) {
			$where['classify'] = $value['id'];
			$data['name'] = $value['name'];
			$data['status'] = $value['status'];
			$data['list'] = $this->field($field)->order('id')->where($where)->select();
			$type && $data['list'] = $this->checkShow($data['list']);
			count($data['list'])>0 && $list[$value['id']] = $data;
		}
		return $list;
    }

	/**
     * 检测是否显示
     * @param  array  $data  列表
     * @return array  $result 检测后列表
     */
	public function checkShow($data)
	{
		$where=[
 			'project_id' => get_project_id(),
 			'duty_id' => get_duty_id(),
 			'position_id' => get_position_id()
 		];
		foreach ($data as $num => $value) {
			$where['type'] = $value['id'];
		 	$count = FlowAuditConfig::where($where)->count();
			if (!$count) {
				unset($data[$num]);
			}
		}
		return $data;
	 }

    /**
     * 获取流程
     * @param  int    $type        流程类型
     * @param  array  $data        表单内容
     * @param  int    $id 		   流程id
     * @return str    $flow        流程
     */
    public function getFlow($type, $data = [], $id = null)
    {
		$user_id = $id ? Flow::where(['id'=>$id])->value('user_id') : get_user_id();
		$where=[
		   'project_id' => get_project_id($user_id),
		   'duty_id' => get_duty_id($user_id),
		   'position_id' => get_position_id($user_id),
		   'type' => $type
	   ];
        $audit_id = FlowAuditConfig::where($where)->value('audit_id');
		$audit_conf = FlowAudit::where(['id'=>$audit_id])->value('audit_conf');
		$audit_conf = explode(',', $audit_conf);
        $audit_conf = $this->verifyFlow($audit_conf, $data);
        $confirm_list = $this->getConfirmList($audit_conf);
        $flow_name = $this->getFlowName($audit_conf);
		$cur_step = 0;
		$id && $cur_step = Flow::where(['id'=>$id])->value('step');
        foreach ($audit_conf as $step => $audit) {
			$audit = explode('|', $audit);
            if ($audit[0] !== 'ur') {
                $flow_name[$step+1] .= '('. $confirm_list[$step]['name'] . ')';
            }
			if ($step == 0 && $cur_step == 0) {
				$flow_name[0] = '【'.$flow_name[0].'】';
			}
			if ($step+1 == $cur_step) {
				$flow_name[$step+1] = '【'.$flow_name[$step+1].'】';
			}
        }

        $flow_list = [
            'flow_name' => $flow_name,
            'confirm_list' => $confirm_list,
        ];
        return $flow_list;
    }

    /**
     * 流程验证
     * @param  array  $flows 流程列表
     * @param  array  $data  数据
     * @return array  $audit_conf 验证后流程列表
     */
    public function verifyFlow($audit_conf, $data = [])
    {
        foreach ($audit_conf as $key => $conf) {
			$conf = explode('|', $conf);
            if (isset($conf[2])) {
				$param = explode('/', $conf[2]);
				!isset($data[$param[0]]) && $data[$param[0]] = 0;
                switch ($param[1]) {
                    case 'elt':
                        if ($data[$param[0]] < $param[2]) {
                            unset($audit_conf[$key]);
                        }
                        break;
                    case 'gt':
                        if ($data[$param[0]] >= $param[2]) {
                            unset($audit_conf[$key]);
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        return $audit_conf;
    }
    /**
     * 获取节点名称
     * @param  array $flows 流程列表
     * @return array        节点名称列表
     */
    public function getFlowName($audit_conf)
    {
        $flow_list[]='发起申请';
        foreach ($audit_conf as $audit) {
			$audit = explode('|', $audit);
            if ($audit[0] == 'po') {
                $position = get_position_name($audit[1]);
                $flow_list[] = $position;
            }
            if ($audit[0] == 'pr') {
                $flow_list[] = '部门负责人';
            }
            if ($audit[0] == 'ur') {
                $user_name = get_user_name($audit[1]);
                $flow_list[] = $user_name;
            }
        }
        $flow_list[] = '审核完成';
        return $flow_list;
    }

    /**
     * 获取审核列表
     * @param  array $flows 流程列表
     * @return array        审核列表
     */
    public function getConfirmList($flows)
    {
        $confirm_list = [];
        foreach ($flows as $flow) {
            $confirm_list[] = $this->_conv_confitm($flow);
        }
        return $confirm_list;
    }

    /**
     * 获取审核人
     * @param  array $flow 流程
     * @return array        审核人
     */
    public function _conv_confitm($flow)
    {
        $User = db('user');
        $confirm = [];
		$audit = explode('|', $flow);
        if ($audit[0] == 'po') {
            $position_id = $audit[1];
            $duty_id = get_duty_id();
            $project_id = get_project_id();
            $where = [];
            $where['duty_id'] = $duty_id;
            $where['position_id'] = $position_id;
            $where['project_id']  = $project_id;
            $user = $User->where($where)->field('id, name')->find();
            if (!empty($user)) {
                $confirm = $user;
            }else {
                $audit[0] = 'pro';
            }
        }

        if ($audit[0] == 'ur') {
            $user_id = $audit[1];
            $where = [];
            $where['id'] = $user_id;
            $user = $User->field('id,name')->where($where)->find();
            if (!empty($user)) {
                $confirm = $user;
            }
        }

        if ($audit[0] == 'pr') {
            $project_id  = isset($audit[1]) ? $audit[1] : get_project_id();
            $leader_id = db('project')->where(['id'=>$project_id])->value('leader_id');
            $where = [];
            $where['id'] = $leader_id;
            $user = $User->field('id,name')->where($where)->find();
            if (!empty($user)) {
                $confirm = $user;
            }
        }

        return $confirm;
    }


    public function getStep($step, $confirm_list)
    {
        $user_id = get_user_id();
        while (true) {
			//判断是否为最后一步
			if (!isset($confirm_list[$step])) {
				$step = ['last',$step];
				break;
			}
            if ($confirm_list[$step]['id'] == $user_id) {
                $step ++;
                if ($step == count($confirm_list)) {
                    break;
                }
            }else {
                break;
            }
        }
        return $step;
    }

    /**
     * 获取流程配置
     * @param  int $type 流程类型
     * @return object       流程配置
     */
    public function getConfig($type)
    {
        $obj = $this->where(['id'=>$type])->find();
        return $obj;
    }
}

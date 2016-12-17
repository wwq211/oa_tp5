<?php
namespace app\index\model;
use app\index\model\Common;

class FlowConfig extends Common
{
    protected $type = [
        'show_position' => 'json',
        'param'         => 'json',
        'set_form'      => 'json',
        'explain'       => 'json',
        'flow_conf'     => 'json',
    ];

    /**
     * 获取流程列表
     * @param  array  $where  查询条件
     * @param  array  $field  查询字段
     * @return array  $result 流程列表
     */
    public function getFlowList($where = array(), $field = array())
    {
        $obj = $this->where($where)->distinct(true)->field('classify')->select();
        foreach ($obj as $value) {
            $where['classify'] = $value['classify'];
            $result[$value['classify']] = $this->field($field)->order('type')->where($where)->select();
        }
        return $result;
    }

    /**
     * 获取流程
     * @param  int    $type        流程类型
     * @param  int    $position_id 职位id
     * @param  array  $data        表单内容
     * @return str    $flow        流程
     */
    public function getFlow($type, $position_id, $data = [])
    {
        $obj = $this->where(['type'=>$type])->field('flow_conf')->find();
        foreach ($obj['flow_conf'] as $value) {
            if (in_array($position_id, $value['position'])) {
                $flows = $value['flow_list'];
            }
        }
        $flows = $this->verifyFlow($flows, $data);
        $confirm_list = $this->getConfirmList($flows);
        $flow_name = $this->getFlowName($flows);
        foreach ($flows as $step => $flow) {
            if ($flow['flow_node'] !== 'pers') {
                $flow_name[$step+1] .= '('. $confirm_list[$step]['name'] . ')';
            }
        }
        $flow_list = [
            'flow_name' => implode(' -> ', $flow_name),
            'confirm_list' => $confirm_list
        ];
        return $flow_list;        
    }

    /**
     * 流程验证
     * @param  array $flows 流程列表
     * @param  array  $data  数据
     * @return array        验证后流程列表
     */
    public function verifyFlow($flows, $data = [])
    {
        foreach ($flows as $key => $flow) {
            if (isset($flow['parame']['condition']) && isset($data[$flow['parame']['condition']['name']])) {
                switch ($flow['parame']['condition']['type']) {
                    case 'elt':
                        if ($data[$flow['parame']['condition']['name']] < $flow['parame']['condition']['value']) {
                            unset($flows[$key]);
                        }
                        break;
                    case 'gt':
                        if ($data[$flow['parame']['condition']['name']] >= $flow['parame']['condition']['value']) {
                            unset($flows[$key]);
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        return $flows;
    }
    /**
     * 获取节点名称
     * @param  array $flows 流程列表
     * @return array        节点名称列表
     */
    public function getFlowName($flows)
    {
        $flow_list[]='发起申请';
        foreach ($flows as $flow) {
            if ($flow['flow_node'] == 'dgp') {
                $flow_list[] = '职能主管';
            }
            if ($flow['flow_node'] == 'dept') {
                $dept = get_dept_name($flow['dept']);
                $position = get_position_name($flow['position']);
                $flow_list[] = $dept.$position;
            }
            if ($flow['flow_node'] == 'dp') {
                $position = get_position_name($flow['position']);
                $flow_list[] = $position;
            }

            if ($flow['flow_node'] == 'pro') {
                $flow_list[] = '部门负责人';
            }
            if ($flow['flow_node'] == 'pers') {
                $user = explode("|",$flow['user']);
                $user_name = $user[0];
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
     * @param  int $value 附加值
     * @return array        审核人
     */
    public function _conv_confitm($flow, $value = null)
    {
        $SysAccount = db('sys_account');
        $confirm = [];
        if ($flow['flow_node'] == 'dept') {
            $position = $flow['position'];
            $dept_id = get_dept_id();
            $where = [];
            $where['dept_id'] = $dept_id;
            $where['position_id'] = $position;
            $user = $SysAccount->where($where)->field('id, name')->find();
            if (!empty($user)) {
                $confirm = $user;
            }
        }

        if ($flow['flow_node'] == 'dp') {
            $position = $flow['position'];
            $dept_id = get_dept_id();
            $project_id = get_project_id();
            $where = [];
            $where['dept_id'] = $dept_id;
            $where['position_id'] = $position;
            $where['project_id']  = $project_id;
            $user = $SysAccount->where($where)->field('id, name')->find();
            if (!empty($user)) {
                $confirm = $user;
            }else {
                $flow['flow_node'] = 'pro';
            }
        }

        if ($flow['flow_node'] == 'pers') {
            $user = explode("|",$flow['user']);
            $user_id = $user[1];
            $where = [];
            $where['id'] = $user_id;
            $user = $SysAccount->field('id,name')->where($where)->find();
            if (!empty($user)) {
                $confirm = $user;
            }
        }

        if ($flow['flow_node'] == 'pro') {
            $project_id  = isset($value['project_id']) ? $value['project_id'] : get_project_id();
            $leader_id = db('sys_project')->where(['id'=>$project_id])->value('leader_id');
            $where = [];
            $where['id'] = $leader_id;
            $user = $SysAccount->field('id,name')->where($where)->find();
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
        $obj = $this->where(['type'=>$type])->find();
        return $obj;
    }
}
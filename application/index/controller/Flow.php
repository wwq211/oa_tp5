<?php
namespace app\index\controller;
use app\index\controller\Base;

class Flow extends Base
{
    public function index()
    {
        $FlowConfig = model('FlowConfig');
        $tag_list = $FlowConfig->getFlowList(['set_status'=>1],['type','name','classify']);
        return $this->fetch('index',['tag_list'=>$tag_list]);
    }

    public function apply()
    {   
        $config = $this->config();
        $data = [
            'title' => $config['name'].date('YmdHis').get_user_name(),
        ];
        $default_data = $this->getDefaultData();
        $default_data && $data += $default_data;
        return $this->fetch('apply', ['data'=>$data, 'config'=>$config]);
    }
}

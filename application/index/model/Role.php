<?php
namespace app\index\model;
use app\index\model\Common;

class Role extends Common
{
    public function users()
    {
        return $this->belongsToMany('User', 'role_user');
    }
}
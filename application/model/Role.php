<?php
namespace app\model;
use app\model\Common;

class Role extends Common
{
    public function users()
    {
        return $this->belongsToMany('User', 'role_user');
    }
}

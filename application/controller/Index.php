<?php
namespace app\controller;
use app\controller\Base;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}

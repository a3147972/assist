<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class FamilyController extends BaseController
{
    public function index()
    {
        //查询直线下属
        $child_map['pid'] = session('user_info.id');
        $child_user_list = D('User')->_list($child_map,'', 'id desc');
        $this->assign('about_list', $about_list);
        $this->display();
    }
}
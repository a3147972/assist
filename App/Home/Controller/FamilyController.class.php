<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class FamilyController extends BaseController
{
    public function index()
    {
        $keyword = I('get.keyword');

        if ($keyword) {
            $child_map['username|name|email|id|phone'] = array('like', '%' . $keyword . '%');
        }
        //查询直线下属
        $child_map['pid'] = session('user_info.id');

        $child_user_list = D('User')->lists($child_map, '', 'id desc');

        $this->assign('child_user_list', $child_user_list);
        $this->display();
    }

    public function deal()
    {
        //查询直线下属
        $child_map['pid'] = session('user_info.id');

        $child_user_list = D('User')->lists($child_map, '', 'id desc');

        $this->assign('child_user_list', $child_user_list);
        $this->display();
    }
}

<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        //查询最新的10条公告
        $about_list = D('About')->_list(array(), '', 'id desc', 1, 10);

        //查询直线下属
        $child_map['pid'] = session('user_info.id');
        $child_user_list = D('User')->_list($child_map, '', 'id desc');
        $this->assign('about_list', $about_list);
        $this->assign('child_user_list', $child_user_list);
        $this->assign('title', '家族系统首页');
        $this->display();
    }
}

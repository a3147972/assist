<?php
namespace Home\Controller;

use Common\Tools\Page;
use Home\Controller\BaseController;

class AboutController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('title', '公告');
    }
    /**
     * 公告列表页
     */
    public function index()
    {
        $page_index = I('p', 1);
        $page_size = 10;

        $model = D('About');
        $list = $model->_list(array(), '', 'id desc', $page_index, $page_size);
        $count = $model->count();

        //分页数组
        $page = new Page($count, $page_index, $page_size);
        $page_list = $page->show();

        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page_list', $page_list);

        $this->display();
    }

    /**
     * 查询单个公告的内容
     */
    public function content()
    {
        $id = I('id');

        $map['id'] = $id;

        $model = D('About');
        $info = $model->_get($map);

        $this->assign('vo', $info);
        $this->display();
    }
}

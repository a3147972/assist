<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;

class UserLevelController extends BaseController
{
    protected $order = 'id asc';

    public function _before_del()
    {
        $level_id = I('id');
        $UserModel = D('User');

        $map['level_id'] = $level_id;

        if ($UserModel->where($map)->field('id')->find()) {
            $this->error('请删除对应级别会员数据再删除会员级别');
        }
    }
}

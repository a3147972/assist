<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;

class AdminController extends BaseController
{
    public function _before_is_enable()
    {
        $id = I('id');

        if ($id == 1) {
            $this->error('超级管理员不可被禁用');
        }
    }
    public function _before_del()
    {
        $id = I('id');

        if ($id == 1) {
            $this->error('超级管理员不可删除');
        }
    }

    /**
     * 状态更改
     * @param int $id 管理员id
     * @param int $status 状态 1-正常 0-禁用
     */
    public function is_enable()
    {
        $id = I('id');
        $status = I('status');

        $model = D('Admin');
        $map['id'] = $id;
        $result = $model->where($map)->setField('is_enable', $status);

        if ($resule) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}

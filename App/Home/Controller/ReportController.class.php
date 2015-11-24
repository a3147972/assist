<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class ReportController extends BaseController
{
    public function index()
    {
        $this->display();
    }
    /**
     * 写入举报记录
     */
    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }
        $order_id = I('post.order_id');
        $user_id = session('user_info.id');
        $to_user_id = I('post.to_user_id');
        $reson_type = I('post.reson_type');
        $reson = I('post.reson');
        $pic = I('post.pic');

        $model = D('Report');
        //查询是否举报过
        $map['order_id'] = $order_id;
        $map['user_id'] = $user_id;
        $map['to_user_id'] = $to_user_id;
        $info = $model->_get($map, 'id');

        if ($info) {
            $this->error('您已经举报过');
        }
        $result = $model->insert($order_id, $user_id, $to_user_id, $reson_type, $reson, $pic);

        if ($result) {
            $this->success('举报成功');
        } else {
            $this->error('举报失败');
        }
    }
}

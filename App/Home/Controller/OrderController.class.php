<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class OrderController extends BaseController
{
    /**
     * 更改订单状态
     */
    public function changeStatus()
    {
        $order_id = I('order_id');
        $status = I('status');

        $model = D('Order');
        $map['order_id'] = $order_id;
        $model->startTrans();
        $result = $model->where($map)->setField('status', $status);
        $status_result = true;
        $status_log_result = true;
        if ($status == 3) {
            //拒绝付款则直接冻结
            $status_result = D('User')->changeStatus(session('user_info.id'), 2);
            //写入状态记录
            $status_log_result = D('UserStatusLog')->insert(session('user_info.id'), 2, '拒绝付款冻结');
        }

        if ($result && $status_result && $status_log_result) {
            $model->commit();
            $this->success('操作成功');
        } else {
            $model->rollback();
            $this->error('操作失败');
        }
    }

    public function updatePic()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $model = D('Order');
        $order_id = I('post.order_id');
        $transfer_pic = I('post.pic');

        $map['order_id'] = $order_id;
        $data['transfer_pic'] = $transfer_pic;

        $result = $model->where($map)->save($data);

        if ($result) {
            $this->success('上传资料成功');
        } else {
            $this->error('上传资料失败');
        }
    }
}

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

    /**
     * 上传转账图片
     */
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

    /**
     * 获取收益会员直接确认成功
     */
    public function ok()
    {
        $order_id = I('order_id');
        $model = D('Order');
        $map['order_id'] = $order_id;
        $order_info = $model->_get($map);

        $assist_info = D('Assist')->_get(array('id' => $order_info['assist_id']));

        $model->startTrans();
        //更改订单状态
        $order_result = $model->where($map)->setField('status', 1);
        //写入提供帮助者账户
        $add_money_result = D('User')->changeRMoney($assist_info['user_id'], $assist_info['money'] * 1.1);
        //写入钱包记录表
        $add_log_result = D('RLog')->insert($assist_info['user_id'], 1, $assist_info['money'] * 1.1, 1, '提供帮助收益,订单号:' . $order_id);

        if ($order_result && $add_money_result && $add_log_result) {
            $model->commit();
            $this->success('确认成功');
        } else {
            $model->rollback();
            $this->error('确认失败');
        }
    }
}

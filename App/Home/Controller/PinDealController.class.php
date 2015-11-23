<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class PinDealController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $user_id = session('user_info.id');
        $username = I('post.username');
        $amount = I('post.amount');

        if (empty($username)) {
            $this->error('请输入目标用户名');
        }
        if (empty($amount)) {
            $this->error('请输入转账数量');
        }
        $to_map['username'] = $username;
        $to_user_id = D('User')->where($to_map)->getField('id');

        if (empty($to_user_id)) {
            $this->error('目标用户不存在');
        }
        if ($amount > session('user_info.pin')) {
            $this->error('您的门票不足');
        }
        $model = D('PinDeal');
        $model->startTrans();
        $result = $model->deal($user_id, $to_user_id, $amount);
        $log_result = D('PinLog')->insert($user_id, 2, 1, $amount, '转账给' . $username);
        $log1_result = D('PinLog')->insert($to_user_id, 1, 1, $amount, session('user_info.username') . '转' . $amount . '张门票给您');

        if ($result && $log_result && $log1_result) {
            $model->commit();
            D('User')->UpdateSessionInfo('pin', session('user_info.pin') - $amount);
            $this->success('转账成功');
        } else {
            $model->rollback();
            $this->error('转账失败');
        }
    }

    public function getName()
    {
        $username = I('username');

        $map['username'] = $username;

        $name = D('User')->where($map)->getField('name');

        if ($name) {
            $this->success($name);
        } else {
            $this->error('error');
        }
    }
}

<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class AssistController extends Basecontroller
{
    /**
     * 写入舍记录
     */
    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $user_id = session('user_info.id');
        $money = I('post.money');
        $pay_password = I('post.pay_password');
        //账号判断
        if (in_array(session('user_info.status'), array(2, 3))) {
            $this->error('账号已被冻结');
        }
        if (md5($pay_password) != session('user_info.pay_password')) {
            $this->error('安全密码不正确');
        }
        $queue_result = $this->checkQuequeCount();
        if ($queue_result == -1) {
            $this->error('您已达到每日最大排队次数');
        }
        if ($queue_result == -2) {
            $this->error('您已达到每月最大排队次数');
        }

        if (empty($money)) {
            $this->error('请输入金额');
        }

        if ($money < 1000 || $money % 1000 != 0) {
            $this->error('金额必须大于1000且为1000的倍数');
        }

        $needPinCount = $money / 1000;

        if ($needPinCount > session('user_info.pin')) {
            $this->error('您的门票不足');
        }

        $model = D('Assist');

        $model->startTrans();

        $changePin_result = D('User')->changePin($user_id, $needPinCount, 2);
        $pin_log_result = D('PinLog')->insert($user_id, 2, 2, $needPinCount, '排队扣除');
        $assist_result = $model->insert($user_id, $money);

        if ($changePin_result && $pin_log_result && $assist_result) {
            $model->commit();
            //更新session中的门票数量
            D('User')->UpdateSessionInfo($user_id, session('user_info.pin') - $needPinCount);
            $this->success('操作成功');
        } else {
            $model->rollback();
            $this->error('操作失败');
        }
    }

    /**
     * 排队次数检测
     */
    private function checkQuequeCount()
    {
        $dayCount = D('Assist')->getDayCount(session('user_info.id'));

        $monthCount = D('Assist')->getMonthCount(session('user_info.id'));

        if ($dayCount >= session('level_info.queue_max_time_day')) {
            //每天最大排队次数
            return -1;
        }

        if ($monthCount >= session('level_info.queue_max_time_month')) {
            //每月最大排队次数
            return -2;
        }
    }
}

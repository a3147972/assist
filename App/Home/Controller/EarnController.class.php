<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class EarnController extends BaseController
{
    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $user_id = session('user_info.id');
        $money = I('post.money');
        $money_type = I('post.money_type');

        if (empty($money)) {
            $this->error('请输入金额');
        }
        if (empty($money_type)) {
            $this->error('请选择钱包类型');
        }
        if ($money < 100 || $money % 100 != 0) {
            $this->error('金额必须是100的倍数并且大于100');
        }

        switch ($money_type) {
            case 1:
                if ($money > session('user_info.c_money')) {
                    $this->error('余额不足');
                }
                break;
            case 2:
                if ($money > session('user_info.r_money')) {
                    $this->error('余额不足');
                }
                break;
        }

        $model = D('Earn');
        $model->startTrans();
        $result = $model->insert($user_id, $money, $money_type);
        switch ($money_type) {
            case 1:
                $deduct_money = D('User')->changeCMoney($user_id, $money, 2);
                $log_result = D('CLog')->insert($user_id, 2, $money, 5, '获得收益扣除');
                break;
            case 2:
                $deduct_money = D('User')->changeRMoney($user_id, $money, 2);
                $log_result = D('RLog')->insert($user_id, 2, $money, 5, '获得收益扣除');
                break;
        }

        if ($result && $deduct_money && $log_result) {
            $model->commit();
            //更新session中的余额
            switch ($money_type) {
                case 1:
                    D('User')->UpdateSessionInfo('c_money', session('user_info.c_money') - $money);
                    break;
                case 2:
                    D('User')->UpdateSessionInfo('r_money', session('user_info.r_money') - $money);
                    break;
            }

            $this->success('操作成功');
        } else {
            $model->rollback();
            $this->error('提交记录失败');
        }
    }
}

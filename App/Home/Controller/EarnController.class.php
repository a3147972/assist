<?php
namespace Home\Controller;

use Common\Tools\Page;
use Home\Controller\BaseController;

class EarnController extends BaseController
{
    public function _filter()
    {
        $map['user_id'] = session('user_info.id');

        return $map;
    }
    public function index()
    {
        $page_index = I('page', 1);
        $page_size = 10;

        $model = D(CONTROLLER_NAME);
        $map = method_exists($this, '_filter') ? $this->_filter() : array();
        if (method_exists($model, 'lists')) {
            $list = $model->lists($map, '', 'id desc', $page_index, $page_size);
        } else {
            $list = $model->_list($map, '', 'id desc', $page_index, $page_size);
        }
        if (!empty($list)) {
            //查找订单
            $orderModel = D('Order');
            $earn_id = array_column($list, 'id');
            $earn_id = array_unique($earn_id);
            $order_map['earn_id'] = array('in', $earn_id);
            $order_map['status'] = array('neq', 0);
            $order_list = $orderModel->lists($order_map);

            if (!empty($order_list)) {
                $user_id = array_column($order_list, 'assist_user_id');
                $user_id = array_filter($user_id);
                $user_id = array_unique($user_id);

                $user_list = D('User')->_list(array('id' => array('in', $user_id)), 'pid');

                $superior_user_id = array_column($user_list, 'pid');
                $superior_user_id = array_filter($superior_user_id);
                $superior_user_id = array_unique($superior_user_id);
                if (!empty($superior_user_id)) {
                    $superior = D('User')->_list(array('id' => array('in', $superior_user_id)), 'id as user_id,name,phone');
                    $superior = array_column($list, null, 'id');
                } else {
                    $superior = array();
                }

                $list = array_column($list, null, 'id');
                foreach ($order_list as $_k => $_v) {
                    if (isset($superior[$_v['assist_user_id']])) {
                        $_v['assist_superior_name'] = $superior[$_v['assist_user_id']]['name'];
                        $_v['assist_superior_phone'] = $superior[$_v['assist_user_id']]['phone'];
                    } else {
                        $_v['assist_superior_name'] = $_v['assist_name'];
                        $_v['assist_superior_phone'] = $_v['assist_phone'];
                    }
                    $list[$_v['earn_id']]['order_list'][] = $_v;
                }
            }
            //查询当前用户的上级用户
            if (session('user_info.pid') == 0) {
                $superior['name'] = session('user_info.name');
                $superior['phone'] = session('user_info.phone');
            } else {
                $superior = D('User')->_get(array('id' => session('user_info.pid'), 'name,phone'));
            }
            $this->assign('superior', $superior);
        }
        $count = $model->_count($map);

        //分页数组
        $page = new Page($count, $page_index, $page_size);
        $page_list = $page->show();

        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page_list', $page_list);

        $this->display();
    }
    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $user_id = session('user_info.id');
        $money = I('post.money');
        $money_type = I('post.money_type');
        $pay_password = I('post.pay_password');

        if (md5($pay_password) != session('user_info.pay_password')) {
            $this->error('安全密码不正确');
        }
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

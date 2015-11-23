<?php
namespace Home\Controller;

use Common\Tools\Page;
use Home\Controller\BaseController;

class AssistController extends Basecontroller
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
            $assist_id = array_column($list, 'id');
            $assist_id = array_unique($assist_id);
            $order_map['assist_id'] = array('in', $assist_id);
            $order_list = $orderModel->lists($order_map);

            if (!empty($order_list)) {
                $user_id = array_column($order_list, 'earn_user_id');
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
                    if (isset($superior[$_v['earn_user_id']])) {
                        $_v['earn_superior_name'] = $superior[$_v['earn_user_id']]['name'];
                        $_v['earn_superior_phone'] = $superior[$_v['earn_user_id']]['phone'];
                    } else {
                        $_v['earn_superior_name'] = $_v['earn_name'];
                        $_v['earn_superior_phone'] = $_v['earn_phone'];
                    }
                    $list[$_v['assist_id']]['order_list'][] = $_v;
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

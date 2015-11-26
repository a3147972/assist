<?php
namespace Home\Controller;

use Common\Tools\Page;
use Home\Controller\BaseController;

class AssistController extends Basecontroller
{
    public function _filter()
    {
        $user_id = I('user_id');
        $map['user_id'] = isset($user_id) && !empty($user_id) ? $user_id : session('user_info.id');

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
            $order_map['status'] = array('neq', 0);
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
            $user_info = D('User')->_get(array('id' => $map['user_id']));
            if ($user_info['pid'] == 0) {
                $superior['name'] = $user_info['name'];
                $superior['phone'] = $user_info['phone'];
            } else {
                $superior = D('User')->_get(array('id' => $user_info['pid'], 'name,phone'));
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

    public function order()
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
            $order_map['status'] = 1;
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
            $user_info = D('User')->_get(array('id' => $map['user_id']));
            if ($user_info['pid'] == 0) {
                $superior['name'] = $user_info['name'];
                $superior['phone'] = $user_info['phone'];
            } else {
                $superior = D('User')->_get(array('id' => $user_info['pid'], 'name,phone'));
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
            $level_id = session('user_info.level_id');
            //获取对应等级里的罚金
            $level_info = D('UserLevel')->_get(array('id' => $level_id));
            //获取冻结或拉黑原因
            $status_map['user_id'] = session('user_info.id');
            $status_map['status'] = session('user_info.status');
            $status_info = D('UserStatusLog')->_get($map, 'desc', 'id desc');

            $error_data['info'] = '您的账号已被';
            $error_data['info'] .= session('user_info.status') == 2 ? '冻结' : '拉黑';
            $error_data['info'] .= '如果想要解除需要扣除';
            $error_data['info'] .= session('user_info.status') == 2 ? '奖金钱包' . $level_info['freeze_c_penalty'] . '元,收益钱包' . $level_info['freeze_r_penalty'] . '元' : '奖金钱包' . $level_info['black_c_penalty'] . '元,收益钱包' . $level_info['black_r_penalty'] . '元';
            $error_data['status'] = -1;
            $this->error($error_data, U('User/black'));
        }
        if (md5($pay_password) != session('user_info.pay_password')) {
            $error_data['status'] = -2;
            $error_data['info'] = '安全密码不正确';
            $this->error($erorr_data);
        }
        $queue_result = $this->checkQuequeCount();
        if ($queue_result == -1) {
            $error_data['status'] = -3;
            $error_data['info'] = '您已达到每日最大排队次数';
            $this->error($erorr_data);
        }
        if ($queue_result == -2) {
            $error_data['status'] = -4;
            $error_data['info'] = '您已达到每月最大排队次数';
            $this->error($erorr_data);
        }

        if (empty($money)) {
            $error_data['status'] = -5;
            $error_data['info'] = '请输入金额';
            $this->error($erorr_data);
        }

        if ($money < 1000 || $money % 1000 != 0) {
            $error_data['status'] = -6;
            $error_data['info'] = '金额必须大于1000且为1000的倍数';
            $this->error($erorr_data);
        }

        $needPinCount = $money / 1000;

        if ($needPinCount > session('user_info.pin')) {
            $error_data['status'] = -7;
            $error_data['info'] = '您的门票不足';
            $this->error($erorr_data);
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
            $error_data['status'] = -8;
            $error_data['info'] = '操作失败';
            $this->error($erorr_data);
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

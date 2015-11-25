<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Common\Tools\ArrayHelper;

class OrderController extends BaseController
{
    public function match()
    {
        $AssistModel = D('Assist');
        $EarnModel = D('Earn');
        //舍列表
        $assist_map['surplus_money'] = array('neq', 0);
        $assist = $AssistModel->_list($assist_map, '', 'id asc');

        //得列表
        $earn_map['surplus_money'] = array('neq', 0);
        $earn = $EarnModel->_list($earn_map, '', 'id asc');

        if (empty($assist) || empty($earn)) {
            $this->success('匹配完成');
        }
        $assist_list = array();
        $earn_list = array();
        //拆分舍和得列表为100
        foreach ($assist as $_k => $_v) {
            $_count = $_v['surplus_money'] / 100;
            for ($i = 0; $i < $_count; $i++) {
                $_assist_list = $_v;
                $_assist_list['surplus_money'] = 100;
                array_push($assist_list, $_assist_list);
            }
        }

        foreach ($earn as $_k => $_v) {
            $_count = $_v['surplus_money'] / 100;
            for ($i = 0; $i < $_count; $i++) {
                $_earn_list = $_v;
                $_earn_list['surplus_money'] = 100;
                array_push($earn_list, $_earn_list);
            }
        }

        //舍和得开始匹配
        foreach ($earn_list as $_k => $_v) {
            //取出提供帮助列表中和获取收益不是同一个用户的数据
            $assist_id = false;
            foreach ($assist_list as $_i => $_j) {
                if ($_j['user_id'] != $_v['user_id']) {
                    $assist_id = $_j['id'];
                    unset($assist_list[$_i]);
                    break;
                }
            }
            if ($assist_id === false) {
                break;
            }
            $earn_list[$_k]['assist_id'] = $assist_id;
        }

        //匹配成订单
        $order_list = array();

        foreach ($earn_list as $_k => $_v) {
            $key = $_v['id'] . '+' . $_v['assist_id'];
            if (isset($order_list[$key])) {
                $order_list[$key]['money'] += $_v['surplus_money'];
            } else {
                $order_list[$key]['assist_id'] = $_v['assist_id'];
                $order_list[$key]['earn_id'] = $_v['id'];
                $order_list[$key]['money'] = $_v['surplus_money'];
            }
        }

        $assist = array_column($assist, null, 'id');
        $earn = array_column($earn, null, 'id');
        foreach ($order_list as $_k => $_v) {
            $assist[$_v['assist_id']]['surplus_money'] -= $_v['money'];
            if ($assist[$_v['assist_id']]['surplus_money'] == 0) {
                $assist[$_v['assist_id']]['status'] = 1;
            }
            $earn[$_v['earn_id']]['surplus_money'] -= $_v['money'];
            if ($earn[$_v['earn_id']]['surplus_money'] == 0) {
                $earn[$_v['earn_id']]['status'] = 1;
            }
        }
        $assist = ArrayHelper::array_number_key($assist);
        $earn = ArrayHelper::array_number_key($earn);
        $order_list = ArrayHelper::array_number_key($order_list);

        $OrderModel = D('Order');
        $OrderModel->startTrans();
        $save_assist_result = $AssistModel->addAll($assist, array(), true);
        $save_earn_result = $EarnModel->addAll($earn, array(), true);
        $save_order_result = $OrderModel->insertAll($order_list);

        if ($save_assist_result && $save_earn_result && $save_order_result) {
            $OrderModel->commit();
            $this->success('匹配成功');
        } else {
            $OrderModel->rollback();
            $this->error('匹配失败');
        }
    }
}

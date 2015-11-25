<?php
namespace Common\Model;

use Common\Model\BaseModel;

class OrderModel extends BaseModel
{
    protected $tableName = 'order';
    protected $selectFields = 'id, order_id,assist_id,earn_id,money,transfer_picstatus,create_time';

    /**
     * 批量写入订单表
     * @param  array $data 数据,array('assist_id'=>'', 'earn_id'=>'', 'money'=>'');
     * @return bool       成功返回true,失败返回false
     */
    public function insertAll($data)
    {
        foreach ($data as $_k => $_v) {
            $data[$_k]['order_id'] = $this->createOrderId();
            $data[$_k]['status'] = 0;
            $data[$_k]['create_time'] = time();
            $data[$_k]['modify_time'] = time();
        }

        $result = $this->addAll($data);

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * 查询订单数据
     * @param  array   $map       查询条件
     * @param  string  $field     查询字段
     * @param  string  $order     排序数组
     * @param  integer $page      页数
     * @param  integer $page_size 每页条数
     * @return array              返回数据
     */
    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
    {
        $list = $this->_list($map, $field, $order, $page, $page_size);

        if (empty($list)) {
            return array();
        }
        //查询援助记录
        $assist_id = array_column($list, 'assist_id');
        $assist_id = array_unique($assist_id);

        $assist_map['id'] = array('in', $assist_id);
        $assist_fields = 'id as assist_id, user_id as assist_user_id';

        $assist_list = D('Assist')->_list($assist_map, $assist_fields);
        $assist_list = array_column($assist_list, null, 'assist_id');
        //查询获取收益记录
        $earn_id = array_column($list, 'earn_id');
        $earn_id = array_unique($earn_id);

        $earn_map['id'] = array('in', $earn_id);
        $earn_fields = 'id as earn_id,user_id as earn_user_id';

        $earn_list = D('Earn')->_list($earn_map, $earn_fields);
        $earn_list = array_column($earn_list, null, 'earn_id');
        //获取会员信息
        $assist_user_id = array_column($assist_list, 'assist_user_id');
        $earn_user_id = array_column($earn_list, 'earn_user_id');
        $user_id = array_merge($assist_user_id, $earn_user_id);

        $user_map['id'] = array('in', $user_id);
        $user_fields = 'id as user_id, phone,username as user_username, name as user_name,alipay_account,bank_name,bank_address,bank_code,bank_account,province,city';
        $user_list = D('User')->_list($user_map, $user_fields);
        $user_list = array_column($user_list, null, 'user_id');

        //合并记录数据
        foreach ($assist_list as $_k => $_v) {
            $assist_list[$_k]['assist_user_id'] = $_v['assist_user_id'];
            $assist_list[$_k]['assist_username'] = $user_list[$_v['assist_user_id']]['user_username'];
            $assist_list[$_k]['assist_name'] = $user_list[$_v['assist_user_id']]['user_name'];
            $assist_list[$_k]['assist_phone'] = $user_list[$_v['assist_user_id']]['phone'];
            $assist_list[$_k]['assist_alipay_account'] = $user_list[$_v['assist_user_id']]['alipay_account'];
            $assist_list[$_k]['assist_bank_name'] = $user_list[$_v['assist_user_id']]['bank_name'];
            $assist_list[$_k]['assist_bank_address'] = $user_list[$_v['assist_user_id']]['bank_address'];
            $assist_list[$_k]['assist_bank_code'] = $user_list[$_v['assist_user_id']]['bank_code'];
            $assist_list[$_k]['assist_bank_account'] = $user_list[$_v['assist_user_id']]['bank_account'];
            $assist_list[$_k]['assist_province'] = $user_list[$_v['assist_user_id']]['province'];
        }
        foreach ($earn_list as $_k => $_v) {
            $earn_list[$_k]['earn_user_id'] = $_v['earn_user_id'];
            $earn_list[$_k]['earn_username'] = $user_list[$_v['earn_user_id']]['user_username'];
            $earn_list[$_k]['earn_name'] = $user_list[$_v['earn_user_id']]['user_name'];
            $earn_list[$_k]['earn_phone'] = $user_list[$_v['earn_user_id']]['phone'];
            $earn_list[$_k]['earn_alipay_account'] = $user_list[$_v['earn_user_id']]['alipay_account'];
            $earn_list[$_k]['earn_bank_name'] = $user_list[$_v['earn_user_id']]['bank_name'];
            $earn_list[$_k]['earn_bank_address'] = $user_list[$_v['earn_user_id']]['bank_address'];
            $earn_list[$_k]['earn_bank_code'] = $user_list[$_v['earn_user_id']]['user_bank_code'];
            $earn_list[$_k]['earn_bank_bank_account'] = $user_list[$_v['earn_user_id']]['bank_account'];
            $earn_list[$_k]['earn_province'] = $user_list[$_v['earn_user_id']]['province'];
            $earn_list[$_k]['earn_city'] = $user_list[$_v['earn_user_id']]['city'];
        }
        //合并订单数据
        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($assist_list[$_v['assist_id']], $earn_list[$_v['earn_id']], $_v);
        }

        return $list;
    }
    /**
     * 创建订单号方法
     * @return int 订单号
     */
    private function createOrderId()
    {
        return date('ymdHis') . rand(10000, 99999);
    }

    /**
     * 更新订单状态
     * @param  int  $order_id    订单号
     * @param  integer $status   状态 0-等待 1-确认完成
     * @return bool              成功返回true,失败返回false
     */
    public function changeStatus($order_id, $status = 1)
    {
        $map['order_id'] = $order_id;

        $result = $this->where($map)->setField('status', $status);

        if ($result) {
            return true;
        }
        return false;
    }
}

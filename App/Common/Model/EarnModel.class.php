<?php
namespace Common\Model;

use Common\Model\BaseModel;
use Common\Tools\ArrayHelper;

class EarnModel extends BaseModel
{
    protected $tableName = 'earn';
    protected $selectFields = 'id,money,surplus_money,money_type,status,create_time';

    /**
     * 写入收益表
     * @param  int  $user_id    会员id
     * @param  int  $money      金额
     * @param  integer $money_type 钱包类型 1-奖金钱包 2-收益钱包
     * @return bool              成功返回true,失败返回false
     */
    public function insert($user_id, $money, $money_type = 1)
    {
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['money_type'] = $money_type;
        $data['surplus_money'] = $money;
        $data['status'] = 0;
        $data['create_time'] = time();
        $data['modify_time'] = time();

        $result = $this->add($data);

        if ($result) {
            return true;
        }
        return false;
    }

    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
    {
        $list = $this->_list($map, $field, $order, $page, $page_size);

        if (empty($list)) {
            return array();
        }
        //查询会员列表数据
        $user_id = array_column($list, 'user_id');

        $user_map['id'] = array('in', $user_id);

        $user_list = D('User')->_list($user_map, 'id, username, name');
        $user_list = ArrayHelper::array_key_replace($user_list, 'id', 'user_id');
        $user_list = array_column($user_list, null, 'user_id');
        //合并数据
        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($_v, $user_list[$_v['user_id']]);
        }

        return $list;
    }
}

<?php
namespace Common\Model;

use Common\Model\BaseModel

class EarnModel extends BaseModel
{
    protected $tableName = 'earn';
    protected $selectFields = 'id,money,match_money,money_type,status,create_time';

    /**
     * 写入收益表
     * @param  int  $user_id    会员id
     * @param  int  $money      金额
     * @param  integer $money_type 钱包类型 1-奖金钱包 2-收益钱包
     * @return bool              成功返回true,失败返回false
     */
    public function insert($user_id, $money,$money_type = 1)
    {
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['money_type'] = $money_type;
        $data['match_money'] = 0;
        $data['status'] = 0;
        $data['create_time'] = time();
        $data['modify_time'] = time();

        $result = $this->add();

        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * 匹配表
     * @param  array $earn_list  匹配金额数据array('id'=>'', 'money'=>'', 'status'=>'')
     * @return bool              成功返回true,失败返回false
     */
    public function matchMoney($earn_list)
    {
        $data = array();

        foreach ($earn_list as $_k => $_v) {
            $_data['id'] = $_v['id'];
            $_data['match_money'] = array('exp', 'match_money + '. $_v['money']);
            $_data['status'] = $_v['status'];
            array_push($data, $_data);
        }

        $result = $this->addAll($data, array(), true);

        if ($result) {
            return true;
        }
        return false;
    }
}
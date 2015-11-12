<?php
namespace Common\Model;

use Common\Model\BaseModel;

class AssistModel extends BaseModel
{
    protected $tableName = 'assist';
    protected $selectFields = 'id,user_id,money,match_money,status,create_time';

    /**
     * 写入援助记录
     * @param  int $user_id 会员id
     * @param  int $money   援助金额
     * @return bool          成功返回true,失败返回false
     */
    public function insert($user_id, $money)
    {
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['match_money'] = 0;
        $data['status'] = 0;
        $data['create_time'] = time();
        $data['modify_time'] = time();

        $result = $this->add($data);

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * 匹配援助金额
     * @param  array $assist_list 援助金额数组,array('assist_id'=>'','money'=>'','status'=>'')
     * @return bool               更新成功返回true,失败返回false
     */
    public function matchMoney($assist_list)
    {
        $data = array();
        foreach ($assist_id_list as $_k => $_v) {
            $_data['id'] = $_v['assist_id'];
            $_data['match_money'] = array('exp', 'match_money + ' . $_v['money']);
            $_data['status'] = $_v['status'];
            array_push($data, $_data);
        }

        $result = $this->addAll($data, array(), true);

        if ($result) {
            return ture;
        }
        return false;
    }
}

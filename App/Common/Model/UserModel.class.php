<?php
namespace Common\Model;

use Common\BaseModel;

class UserModel extends BaseModel
{
    protected $tableName = 'user';
    protected $selectFields = 'id, pid, level_id, username, password, pay_password, c_money, r_money, pin, name, age, sex, phone, email, province, city, alipay_account, bank_name, bank_address, bank_code, bank_account, iban_code, status, create_time';

    public function getFieldByUserId($user_id, $field)
    {
        return $this->where(array('id' => $user_id))->getField($field);
    }

    /**
     * 更改门票数量
     *
     * @param int $user_id 会员id
     * @param int $pin     门票数量
     * @param int $type    操作类型 1-增加 2-减少
     * @return  bool       成功返回true,失败返回false
     */
    public function changePin($user_id, $pin, $type = 1)
    {
        $map['id'] = $user_id;
        switch ($type) {
            case 1:
                $data['pin'] = array('exp', 'pin + ' . $pin);
                break;
            case 2:
                $data['pin'] = array('exp', 'pin - ' . $pin);
                break;
        }

        $result = $this->where($map)->save($data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更改奖金钱包
     * @param  int  $user_id 会员id
     * @param  int  $c_money 操作金额
     * @param  integer $type    操作类型 1-增加 2-减少
     * @return bool           成功返回true,失败返回false
     */
    public function changeCMoney($user_id, $c_money, $type = 1)
    {
        $map['id'] = $user_id;
        switch ($type) {
            case 1:
                $data['c_money'] = array('exp', 'c_money + ' . $c_money);
                break;
            case 2:
                $data['c_money'] = array('exp', 'c_money - ' . $c_money);
                break;
        }

        $result = $this->where($map)->save($data);

        if ($result) {
            return true;

        }

        return false;
    }

    /**
     * 更改奖金钱包
     * @param  int  $user_id 会员id
     * @param  int  $c_money 操作金额
     * @param  integer $type    操作类型 1-增加 2-减少
     * @return bool           成功返回true,失败返回false
     */
    public function changeRMoney($user_id, $r_money, $type = 1)
    {
        $map['id'] = $user_id;
        switch ($type) {
            case 1:
                $data['r_money'] = array('exp', 'r_money + ' . $r_money);
                break;
            case 2:
                $data['r_money'] = array('exp', 'r_money - ' . $r_money);
                break;
        }

        $result = $this->where($map)->save($data);

        if ($result) {
            return true;
        }

        return false;
    }
}

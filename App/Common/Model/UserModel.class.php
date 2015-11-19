<?php
namespace Common\Model;

use Common\Model\BaseModel;
use Common\Tools\ArrayHelper;

class UserModel extends BaseModel
{
    protected $tableName = 'user';
    protected $selectFields = 'id, pid, level_id, username, password, pay_password, c_money, r_money, pin, name, phone, email, province, city, alipay_account, bank_name, bank_address, bank_code, bank_account, iban_code, status, create_time';

    protected $_validate = array(
        array('username', 'require', '请输入用户名', 1),
        array('username', '', '用户已存在', 0, 'unique', 1),
        array('password', 'validate_password', '请输入密码', 1, 'callback'),
        array('pay_password', 'validate_password', '请输入支付密码', 1, 'callback'),
        array('name', 'require', '请输入姓名', 1),
        array('phone', 'require', '请输入联系电话', 1),
        array('email', 'require', '请输入邮箱', 1),
        array('province', 'require', '请选择身份', 1),
        array('city', 'require', '请选择市区', 1),
        array('alipay_account', 'require', '请输入支付宝账号', 1),
        array('bank_name', 'require', '请输入银行名称', 1),
        array('bank_address', 'require', '请输入开户行', 1),
        array('bank_code', 'require', '请输入银行卡号', 1),
        array('bank_account', 'require', '请输入户主', 1),
    );

    protected $_auto = array(
        array('c_money', 0, 1, 'string'),
        array('r_money', 0, 1, 'string'),
        array('level_id', 1, 1, 'string'),
        array('pin', 0, 1, 'string'),
        array('create_time', 'time', 1, 'function'),
        array('modify_time', 'time', 3, 'function'),
        array('password', 'auto_password', 3, 'callback'),
        array('pay_password', 'auto_password', 3, 'callback'),
        array('password', '', 2, 'ignore'),
        array('pay_password', '', 2, 'ignore'),
    );

    protected function validate_password($v)
    {
        $id = I('post.id');
        if (empty($id) && empty($v)) {
            return false;
        }
        return true;
    }

    public function auto_password($v)
    {
        $id = I('post.id');

        if (empty($id)) {
            return md5($v);
        } else {
            if (empty($v)) {
                return '';
            } else {
                return md5($v);
            }
        }
    }

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

    /**
     * 更改会员状态
     * @param  int  $user_id 会员id
     * @param  integer $status  状态 s1-正常 0-禁用 2-冻结 3-拉黑
     * @return bool            成功返回true,失败返回false
     */
    public function changeStatus($user_id, $status = 1)
    {
        $map['id'] = $user_id;

        $result = $this->where($map)->setField('status', $status);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 查询多条数据
     * @method _list
     * @param  array   $map       查询条件
     * @param  string  $field     查询字段
     * @param  string  $order     排序规则,默认主键倒序
     * @param  integer $page      分页数,默认1
     * @param  integer $page_size 分页条数,默认10
     * @return array              查询出的数据
     */
    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
    {
        $list = $this->_list($map = array(), $field = '', $order = '', $page = 0, $page_size = 10);

        if (empty($list)) {
            return array();
        }

        //查询会员级别表数据
        $level_id = array_column($list, 'level_id');
        $level_id = array_unique($level_id);

        $level_map['id'] = array('in', $level_id);
        $level_fields = 'id as level_id, name as level_name';
        $level_list = D('UserLevel')->_list($level_map, $level_fields);
        $level_list = array_column($level_list, null, 'level_id');

        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($_v, $level_list[$_v['level_id']]);
        }

        return $list;
    }

    /**
     * 登录方法
     */
    public function login($username, $password)
    {
        $map['username'] = $username;
        $map['status'] = array('not in', '0,-1');

        $info = $this->get($map);

        if (empty($info)) {
            $this->error = '用户不存在或已被禁用';
            return false;
        }

        if ($info['password'] != md5($password)) {
            $this->error = '密码不正确';
            return false;
        }

        return $info;
    }

    public function get($map = array(), $fields = '', $order = '')
    {
        $info = $this->_get($map);

        if (empty($info)) {
            return false;
        }

        $level_map['id'] = $info['level_id'];
        $level_info = D('UserLevel')->_get($level_map);

        $level_info = ArrayHelper::array_key_replace($level_info, array('id', 'name'), array('level_id', 'level_name'));

        $info = array_merge($info, $level_info);

        return $info;
    }

    /**
     * 更新session中的用户信息
     * @param string $field 要更新的用户信息字段
     * @param obj $value 值
     */
    public function UpdateSessionInfo($field, $value)
    {
        $user_info = session('user_info');

        $user_info[$field] = $value;

        session('user_info', $user_info);

        return true;
    }

    /**
     * 会员级别升级
     * @param int $user_id  会员id
     * @param int $level_id 当前会员级别
     */
    public function Upgrade($user_id)
    {
        $map['id'] = $user_id;
        $level_id = $this->where($map)->getField('level_id');
        $superior = D('UserLevel')->getSuperior($level_id);

        if ($superior) {
            $result = $this->where($map)->setField('level_id', $superior['id']);
            if ($result) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}

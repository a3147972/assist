<?php
namespace Common\Model;

use Common\Model\BaseModel;

class AdminModel extends BaseModel
{
    protected $tableName = 'admin';
    protected $selectFields = 'id,username,password,nickname,is_enable,create_time';

    protected $_validate = array(
        array('username', 'require', '请输入用户名', 1),
        array('nickname', 'require', '请输入昵称', 1),
        array('password', 'validate_password', '请输入密码', 1, 'callback'),
        array('password', '6,16', '密码最短6位，最长16位', 2, 'length'),
    );
    protected $_auto = array(
        array('is_enable', 1, 1, 'string'),
        array('password', 'auto_password', 3, 'callback'),
        array('password', '', 2, 'ignore'),
        array('create_time', 'time', 1, 'function'),
        array('modify_time', 'time', 3, 'function'),
    );

    protected function validate_password($v)
    {
        $id = I('post.id');

        if (empty($id) && empty($v)) {
            return false;
        }
        return true;
    }
    protected function auto_password($v)
    {
        if (!empty($v)) {
            return md5($v);
        }
        return '';
    }
    /**
     * 后台管理员登录方法
     * @param  string $username 用户名
     * @param  string $password 密码
     * @return array|bool           成功返回用户信息,失败返回false
     */
    public function login($username, $password)
    {
        $map['username'] = $username;
        $map['is_enable'] = 1;
        $info = $this->_get($map);

        if (empty($info)) {
            $this->error = '用户不存在';
            return false;
        }

        if ($info['password'] != md5($password)) {
            $this->error = '密码不正确';
            return false;
        }

        return $info;
    }
}

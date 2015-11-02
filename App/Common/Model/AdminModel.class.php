<?php
namespace Common\Model;

use Common\Model\BaseModel;

class AdminModel extends BaseModel
{
    protected $tableName = 'admin';
    protected $selectFields = 'id,username,password,nickname,is_enable,create_time';
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

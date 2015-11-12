<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class UserController extends BaseController
{
    /**
     * 管理档案
     */
    public function index()
    {
        $this->display();
    }

    /**
     * 新增用户
     */
    public function insert()
    {
        $model = D('User');

        if (!$model->create()) {
            $this->error($model->getError());
        }

        $result = $model->add();

        if ($result) {
            $this->success('注册成功');
        } else {
            $this->error('注册失败');
        }
    }

    /**
     * 修改个人资料
     */
    public function changeInfo()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $name = I('post.name');
        $email = I('post.email');
        $mobile = I('post.mobile');

        if (empty($name)) {
            $this->error('请输入姓名');
        }
        if (empty($email)) {
            $this->error('请输入邮箱');
        }
        if (empty($mobile)) {
            $this->error('请输入手机号');
        }

        $map['id'] = session('user_info.id');

        $data['name'] = $name;
        $data['email'] = $email;
        $data['mobile'] = $mobile;

        $result = $model->where($map)->save($data);

        if ($result) {
            $this->success('更新成功');
        } else {
            $this->error('更新失败');
        }
    }

    /**
     * 修改安全密码
     */
    public function changePayPwd()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $new_pay_password = I('post.new_pay_password');
        $rep_pay_password = I('post.rep_pay_password');
        $pay_password = I('post.pay_password');

        if (empty($new_pay_password)) {
            $this->error('请输入新安全密码');
        }
        if (empty($rep_pay_password)) {
            $this->error('请再次输入新安全密码');
        }
        if (empty($pay_password)) {
            $this->error('请输入原安全密码');
        }
        if ($new_password != $pay_password) {
            $this->error('两次输入安全密码不一致');
        }
        if (md5($pay_password) != session('user_info.pay_password')) {
            $this->error('原安全密码不正确');
        }

        $map['id'] = session('user_info.user_id');
        $data['pay_password'] = md5($new_pay_password);
        $model = D('User');
        $result = $model->where($map)->save($data);

        if ($result) {
            $model->changeSessionInfo('pay_password', $data['pay_password']);
            $this->success('修改安全密码成功');
        } else {
            $this->error('修改安全密码失败');
        }
    }

    /**
     * 修改登录密码
     */
    public function changePwd()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $password = I('post.password');
        $new_password = I('post.new_password');
        $rep_password = I('post.rep_password');

        if (empty($password)) {
            $this->error('请输入原密码');
        }
        if (empty($new_password)) {
            $this->error('请输入新密码');
        }

        if (empty($rep_password)) {
            $this->error('请再次输入新密码');
        }
        if ($new_password != $rep_password) {
            $this->error('两次输入密码不一致');
        }

        $map['id'] = session('user_info.id');

        $data['password'] = md5($new_password);
        $model = D('User');
        $result = $model->where($map)->save($data);

        if ($result) {
            $this->success('修改密码成功');
        } else {
            $this->error('修改密码失败');
        }
    }

    /**
     * 终止账号
     */
    public function OverAccount()
    {
        $map['id'] = session('user_info.id');

        $result = $model->where($map)->setField('status', -1);

        if ($result) {
            session(null);
            session_regenerate_id();
            $this->success('终止成功', U('Login/index'));
        } else {
            $this->error('终止失败');
        }
    }
}

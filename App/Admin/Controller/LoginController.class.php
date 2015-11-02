<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        if (session('uid')) {
            redirect(U('Index/index'));
        } else {
            $this->display();
        }
    }

    /**
     * 检测登录
     */
    public function checkLogin()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $username = I('post.username');
        $password = I('post.password');

        if (empty($username)) {
            $this->error('请输入用户名');
        }
        if (empty($password)) {
            $this->error('请输入密码');
        }

        $model = D('Admin');

        $info = $model->login($username, $password); //判断登录
        if (!$info) {
            $this->error($model->getError());
        }
        session('uid', $info['id']);
        session('nickname', $info['nickname']);

        $this->success('登录成功', U('Index/index'));
    }

    public function _empty()
    {
        if (session('uid')) {
            redirect(U('Index/index'));
        } else {
            redirect(U('Login/index'));
        }
    }

    /**
     * 退出
     */
    public function logout()
    {
        session_regenerate_id();
        session(null);
        cookie(null);

        redirect(U('Login/index'));
    }
}

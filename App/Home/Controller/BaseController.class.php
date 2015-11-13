<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        if (!session('user_info.id')) {
            redirect(U('Login/index'));
            exit();
        }
        //判断账号是否拉黑/冻结
        if (session('user_info.status') != 1) {
            redirect(U('Login/black'));
            exit();
        }
    }
}

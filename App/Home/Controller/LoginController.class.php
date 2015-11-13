<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        if (!session('user_info.id')) {
            redirect(U('Index/index'));
        } else {
            $this->display();
        }
    }

    /**
     * 账号被拉黑冻结
     */
    public function black()
    {
        $level_id = session('user_info.level_id');
        //获取对应等级里的罚金
        $level_info = D('UserLevel')->_get(array('id' => $level_id));
        //获取冻结或拉黑原因
        $status_map['user_id'] = session('user_info.id');
        $status_map['status'] = session('user_info.status');
        $status_info = D('UserStatusLog')->_get($map, 'desc', 'id desc');

        $this->assign('status', session('user_info.status'));
        $this->assign('reson', $status_info['desc']);
        $this->assign('level_info', $level_info);
        $this->display();
    }

    /**
     * 恢复账号正常状态
     */
    public function nomal()
    {
        $level_id = session('user_info.level_id');
        //获取对应等级里的罚金
        $level_info = D('UserLevel')->_get(array('id' => $level_id));

        switch (session('user_info.status')) {
            case 2:
                $c_penalty = $level_info['freeze_c_penalty'];
                $r_penalty = $level_info['freeze_r_penalty'];
                break;
            case 3:
                $c_penalty = $level_info['black_c_penalty'];
                $r_penalty = $level_info['black_r_penalty'];
                break;
        }

        if (session('user_info.c_money') < $c_penalty) {
            $this->error('您的奖金钱包余额不足,请联系管理员充值后操作');
        }
        if (session('user_info.r_money') < $r_penalty) {
            $this->error('您的收益钱包余额不足,请联系管理员充值后操作');
        }

        $model = D('User');
        $model->startTrans();
        //扣除奖金钱包
        $deduct_c = $model->changeCMoney(session('user_info.id'), $c_penalty, 2);
        $add_c_log = D('CLog')->insert(session('user_info.id'), 2, $c_penalty, 4, '账号惩罚扣除');
        //扣除收益钱包
        $deduct_r = $model->changeRMoney(session('user_info.id'), $r_penalty, 2);
        $add_r_log = D('RLog')->insert(session('user_info.id'), 2, $r_penalty, 3, '账号惩罚扣除');
        //更改会员状态
        $change_status = $model->changeStatus(session('user_info.id'), 1);
        $add_status_log = D('UserStatusLog')->insert(session('user_info.id', 1, '接受惩罚恢复正常'));

        if ($deduct_c && $add_c_log && $deduct_r && $add_r_log && $change_status && $add_status_log) {
            $model->commit();
            //重新查询会员信息,更新到session中
            $user_info = $model->_get(session('user_info.id'));
            session('user_info', $user_info);
            $this->success('操作成功', U('Index/index'));
        } else {
            $model->rollback();
            $this->error('操作失败,请联系管理员');
        }
    }

    /**
     * 登录
     */
    public function checkLogin()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }

        $username = I('post.username');
        $password = I('post.password');

        $model = D('User');

        $info = $model->login($username, $password);

        if (!$info) {
            $this->error($model->getError());
        }

        session('user_info', $info);
        $this->success('登录成功', U('Index/index'));
    }

    /**
     * 退出
     */
    public function logout()
    {
        session(null);
        session_regenerate_id();
        redirect(U('Login/index'));
    }

    public function _empty()
    {
        redirect(U('Login/index'));
    }
}

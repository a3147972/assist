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

    public function info()
    {
        $this->display();
    }

    /**
     * 新增用户
     */
    public function insert()
    {
        if (!IS_POST) {
            $this->error('非法访问');
        }
        $model = D('User');
        $self_password = I('post.self_password');
        if (md5($self_password) != session('user_info.pay_password')) {
            $this->error('您的安全密码不正确');
        }
        if (!$model->create()) {
            $this->error($model->getError());
        }
        $rep_password = I('post.rep_password');
        $password = I('post.password');
        $pay_password = I('post.pay_password');
        $rep_pay_password = I('post.rep_pay_password');
        if (empty($rep_password)) {
            $this->error('请再次输入登录密码');
        }
        if (empty($rep_pay_password)) {
            $this->error('请再次输入安全密码');
        }
        if ($password != $rep_password) {
            $this->error('您输入的两次登录密码不一致');
        }
        if ($pay_password != $rep_pay_password) {
            $this->error('您输入的两次安全密码不一致');
        }
        //推荐收益
        $recommend_reward = D('UserLevel')->where(array('id' => session('user_info.level_id')))->getField('recommend_reward');
        $recommend_reward = intval(1000 * $recommend_reward / 100);

        $model->startTrans();
        $result = $model->add();
        $add_c = $model->changeCMoney(session('user_info.id'), $recommend_reward); //写入推荐收益
        $add_c_log = D('CLog')->insert(session('user_info.id'), 1, $recommend_reward, 1, '会员' . I('post.username') . '注册推荐奖励'); //写入推荐收益奖励

        if ($result && $add_c && $add_c_log) {
            $model->commit();
            $this->success('注册成功');
        } else {
            $model->rollback();
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
        $pay_password = I('post.pay_password');

        if (md5($pay_password) != session('user_info.pay_password')) {
            $this->error('安全密码不正确');
        }
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

        if (md5($pay_password) != session('user_info.pay_password')) {
            $this->error('原安全密码不正确');
        }
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

}

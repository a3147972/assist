<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;

class UserController extends BaseController
{
    public function _before_add()
    {
        $level_list = D('UserLevel')->_list(array(), '', 'id asc');
        $this->assign('level_list', $level_list);
    }

    public function _before_edit()
    {
        $this->_before_add();
    }

    /**
     * 更改奖金钱包数据
     *
     * @param int $user_id 会员id
     * @param int $type 操作类型 1-新增 2-减少
     * @param int $money 金额
     * @param int $desc 描述
     */
    public function changeCMoney()
    {
        if (IS_POST) {
            $user_id = I('post.user_id');
            $type = I('post.type');
            $money = I('post.money');
            $desc = I('post.desc', '管理员操作');

            $model = D('User');
            $CLogModel = D('CLog');
            $model->startTrans();

            $result = $model->changeCMoney($user_id, $money, $type);
            $add_log = $CLogModel->insert($user_id, $type, $money, 6, $desc);

            if ($result !== false && $add_log !== false) {
                $model->commit();
                $this->success('更新数据成功', U('User/index'));
            } else {
                $model->rollback();
                $this->error('更新数据失败');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 更改收益钱包数据
     *
     * @param int $user_id 会员id
     * @param int $type 操作类型 1-新增 2-减少
     * @param int $money 金额
     * @param int $desc 描述
     */
    public function changeRMoney()
    {
        if (IS_POST) {
            $user_id = I('post.user_id');
            $type = I('post.type');
            $money = I('post.money');
            $desc = I('post.desc', '管理员操作');

            $model = D('User');
            $RLogModel = D('RLog');
            $model->startTrans();

            $result = $model->changeRMoney($user_id, $money, $type);
            $add_log = $RLogModel->insert($user_id, $type, $money, 2, $desc);

            if ($result !== false && $add_log !== false) {
                $model->commit();
                $this->success('更新数据成功', U('User/index'));
            } else {
                $model->rollback();
                $this->error('更新数据失败');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 更改门票
     *
     * @param int $user_id 会员id
     * @param int $type 操作类型 1-新增 2-减少
     * @param int $amount 数量
     * @param int $desc 描述
     */
    public function changePin()
    {
        if (IS_POST) {
            $user_id = I('post.user_id');
            $type = I('post.type');
            $amount = I('post.amount');
            $desc = I('post.desc', '管理员操作');

            $model = D('User');
            $PinLogModel = D('PinLog');
            $model->startTrans();

            $result = $model->changePin($user_id, $amount, $type);
            $add_log = $PinLogModel->insert($user_id, $type, 3, $amount, $desc);

            if ($result !== false && $add_log !== false) {
                $model->commit();
                $this->success('更新数据成功', U('User/index'));
            } else {
                $model->rollback();
                $this->error('更新数据失败');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 更改会员状态
     *
     * @param int $user_id 会员id
     * @param int $status 更改状态
     * @param string $desc    描述
     */
    public function changeStatus()
    {
        if (IS_POST) {
            $model = D('User');

            $user_id = I('post.user_id');
            $status = I('post.status');
            $desc = I('post.desc');
            if (empty($desc)) {
                $this->error('请输入操作描述');
            }
            $model->startTrans();
            $result = $model->changeStatus($user_id, $status);
            $add_log = D('UserStatusLog')->insert($user_id, $status, $desc);

            if ($result && $add_log) {
                $model->commit();
                $this->success('更新会员状态成功', U('User/index'));
            } else {
                $model->rollback();
                $this->error('更新状态失败');
            }
        } else {
            $this->display();
        }
    }
}

<?php
namespace Common\Model;

use Common\Model\BaseModel;

class PinDealModel extends BaseModel
{
    protected $tableName = 'pin_deal';
    protected $selectFields = 'id, user_id, to_user_id, amount, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择要操作的会员', 1),
        array('to_user_id', 'require', '请选择要转给的会员', 1),
        array('amount', 'require', '请选择要操作的数量', 1),
        array('amount', 'validate_require', '您的门票不足', 1, 'callback'),
    );

    protected function validate_require($v)
    {
        $user_id = I('post.user_id');
        $pin_count = D('User')->getFieldByUserId($user_id, 'pin');

        if ($pin_count < $v) {
            return false;
        }
        return true;
    }
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 门票交易记录
     * @param  int $user_id    交易人
     * @param  int $to_user_id 被交易人
     * @param  int $amount     数量
     * @return bool             成功返回true，失败返回false
     */
    public function deal($user_id, $to_user_id, $amount)
    {
        $data['user_id'] = $user_id;
        $data['to_user_id'] = $to_user_id;
        $data['amount'] = $amount;

        if (!$this->create($data)) {
            return false;
        }
        $UserModel = D('User');
        $PinLogModel = D('PinLog');

        $to_username = $UserModel->getFieldByUserId($to_user_id);
        $username = $UserModel->getFieldByUserId($user_id);

        $this->startTrans();
        //写入记录
        $result = $this->add($data);
        $addPin = $UserModel->changePin($to_user_id, $amount);   //增加被赠与人门票
        $delPin = $UserModel->changePin($user_id, $amount, 2);   //减少被赠与人门票
        $addPinLog = $PinLogModel->insert($to_user_id, 1, 1, $amount, '用户'.$username .'赠送给您'. $amount.'张门票');
        $delPinLog = $PinLogModel->insert($user_id, 2, 1, $amount, '您赠送给'.$to_username .'用户'. $amount.'张门票');

        if ($result == false) {
            $this->error = '交易失败';
            $this->rollback();
            return false;
        }
        if ($addPin == false || $delPin == false) {
            $this->error = $UserModel->getError();
            $this->rollback();
            return false;
        }
        if ($addPinLog == false || $delPinLog == false) {
            $this->error = $PinLogModel->getError();
            $this->rollback();
            return false;
        }

        $this->commit();
        return true;
    }
}

<?php
namespace Common\Model;

use Common\BaseModel;

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
}

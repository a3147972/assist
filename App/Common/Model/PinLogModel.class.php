<?php
namespace Common\Model;

use Common\Model\BaseModel;

class PinLogModel extends BaseModel
{
    protected $tableName = 'pin_log';
    protected $selectFields = 'id, user_id, type, source, amount, desc, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择要操作的会员'),
        array('type', 'require', '请选择要进行的操作'),
        array('source', 'require', '请选择操作来源'),
        array('amount', 'require', '请输入操作数量'),
        array('desc', 'require', '请输入描述'),
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 门票操作记录
     * @param  int  $user_id 用户id
     * @param  integer $type    操作类型 1-增加 2-减少
     * @param  integer $source  来源 1-赠送 2-使用 3-后台操作
     * @param  integer $amount  数量
     * @param  string  $desc    描述
     * @return bool           成功返回true,失败返回false
     */
    public function insert($user_id, $type = 1, $source = 1, $amount = 1, $desc = '')
    {
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['source'] = $source;
        $data['amount'] = $amount;
        $data['desc'] = $desc;

        if ($this->create($data)) {
            return false;
        }

        $result = $this->add($data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}

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

        if (!$this->create($data)) {
            return false;
        }

        $result = $this->add();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 门票操作记录
     * @param  array   $map       查询条件
     * @param  string  $field     查询字段
     * @param  string  $order     排序规则
     * @param  integer $page      页数
     * @param  integer $page_size 每页条数
     * @return array              查询出的数据
     */
    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
    {
        $list = $this->_list($map, $field, $order, $page, $page_size);

        if (empty($list)) {
            return array();
        }

        $user_id = array_column($list, 'user_id');
        $user_id = array_unique($user_id);

        $user_map['id'] = array('in', $user_id);
        $user_fields = 'id as user_id,username as user_username,name as user_name';
        $user_list = D('User')->_list($user_map, $user_fields);
        $user_list = array_column($user_list, null, 'user_id');

        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($user_list[$_v['user_id']], $_v);
        }

        return $list;
    }
}

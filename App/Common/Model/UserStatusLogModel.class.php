<?php
namespace Common\Model;

use Common\Model\BaseModel;
use Common\Tools\ArrayHelper;

class UserStatusLogModel extends BaseModel
{
    protected $tableName = 'user_status_log';
    protected $selectFields = 'id, user_id, status, desc, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择要操作的会员', 1),
        array('status', 'require', '请选择要执行的操作', 1),
        array('desc', 'require', '请输入操作描述', 1),
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 写入更改会员状态记录
     *
     * @param  integer  $user_id 会员id
     * @param  integer $status  状态 1-正常 0-禁用 2-冻结 3-拉黑
     * @param  string  $desc    描述
     * @return bool             成功返回true,失败返回false
     */
    public function insert($user_id, $status = 1, $desc = '')
    {
        $UserModel = D('User');

        $data['user_id'] = $user_id;
        $data['status'] = $desc;
        $data['desc'] = $desc;

        if (!$this->create($data)) {
            return false;
        }

        $user_map['user_id'] = $user_id;
        $add_status = $this->add();

        if ($add_status !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function lists()
    {
        $list = $this->_list($map, $field, $order, $page, $page_size);

        if (empty($list)) {
            return array();
        }
        //查询会员列表数据
        $user_id = array_column($list, 'user_id');

        $user_map['id'] = array('in', $user_id);

        $user_list = D('User')->_list($user_map, 'id, username, name');
        $user_list = ArrayHelper::array_key_replace($user_list, 'id', 'user_id');
        $user_list = array_column($user_list, null, 'user_id');
        //合并数据
        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($_v, $user_list[$_v['user_id']]);
        }

        return $list;
    }
}

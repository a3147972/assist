<?php
namespace Common\Model;

use Common\BaseModel;

class LetterModel extends BaseModel
{
    protected $tableName = 'letter';
    protected $selectFields = 'id, user_id, to_user_id, title, content, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择要操作的会员', 1),
        array('to_user_id', 'require', '请选择要发给的会员', 1),
        array('title', 'require', '请输入站内信标题'),
        array('content', 'require', '请输入站内信内容'),
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 发送站内信
     * @param  int $user_id    发送会员id
     * @param  int $to_user_id 接收会员id
     * @param  string $title      站内信标题
     * @param  text $content    内容
     * @return bool             成功返回true,失败返回false
     */
    public function send($user_id, $to_user_id, $title, $content)
    {
        $data['user_id'] = $user_id;
        $data['to_user_id'] = $to_user_id;
        $data['title'] = $title;
        $data['content'] = $content;

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
     * 站内信记录
     * @param  array   $map       查询条件
     * @param  string  $field     查询字段
     * @param  string  $order     排序规则
     * @param  integer $page      页数
     * @param  integer $page_size 每页条数
     * @return array             记录数据
     */
    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
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

        $to_user_list = ArrayHelper::array_key_replace($user_list, array('user_id', 'username', 'name'), array('to_user_id', 'to_username', 'to_name'));
        //合并数据
        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($_v, $user_list[$_v['user_id']], $to_user_list[$_v['to_user_id']]);
        }

        return $list;
    }
}

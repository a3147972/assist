<?php
namespace Common\Model;

use Common\Model\BaseModel;

class ReportModel extends BaseModel
{
    protected $tableName = 'report';
    protected $selectFields = 'id,order_id,user_id,to_user_id,reson_type,reson,pic,status,create_time';

    /**
     * 写入举报记录
     * @param  varchar  $order_id   订单id
     * @param  int  $user_id    举报人
     * @param  int  $to_user_id 被举报人
     * @param  integer $reson_type 举报原因：1-对方未打款 2-对方未确认 3-对方账号有误 4-联系方式有误 0-其他
     * @param  string  $reson      举报原因
     * @param  string  $pic        举报图片
     * @return bool              成功返回true,失败返回false
     */
    public function insert($order_id, $user_id, $to_user_id, $reson_type = 0, $reson = '', $pic = '')
    {
        if (empty($order_id)) {
            $this->error = '请选择订单id';
            return false;
        }
        if (empty($user_id)) {
            $this->error = '举报人不可为空';
            return false;
        }
        if (empty($to_user_id)) {
            $this->error = '被举报人不可为空';
            return false;
        }

        $data['order_id'] = $order_id;
        $data['user_id'] = $user_id;
        $data['to_user_id'] = $to_user_id;
        $data['reson_type'] = $reson_type;
        $data['reson'] = $reson;
        $data['pic'] = $pic;
        $data['status'] = 0;
        $data['create_time'] = time();
        $data['modify_time'] = time();

        $result = $this->add($data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更改举报状态
     * @param  int  $id     id
     * @param  integer $status 状态 1:审核通过 -1：审核不通过
     * @return bool          成功返回true,失败返回false
     */
    public function changeStatus($id, $status = 1)
    {
        $map['id'] = $id;

        $result = $this->where($map)->setField('status', $status);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取单个举报信息
     * @param  array  $map    查询条件
     * @param  string $fields 查询字段
     * @param  string $order  排序规则
     * @return array          查询出的数据
     */
    public function get($map = array(), $fields = '', $order = '')
    {
        $info = $this->_get($map, $fields, $order);
        if (empty($info)) {
            return array();
        }

        $user_id = array($info['user_id'], $info['to_user_id']);

        $user_map['id'] = array('in', $user_id);
        $user_fields = 'id as user_id,username as user_name, name as ';
        $user_list = D('User')->_get($user_map, $user_fields);
        $user_list = array_column($user_list, null, 'user_id');

        $info['user_username'] = $user_list[$info['user_id']]['username'];
        $info['user_name'] = $user_list[$info['user_id']]['name'];
        $info['to_user_username'] = $user_list[$info['to_user_id']]['username'];
        $info['to_user_name'] = $user_list[$info['to_user_id']]['name'];

        return $info;
    }

    /**
     * 多条查询
     * @param  array   $map       查询条件
     * @param  string  $field     查询字段
     * @param  string  $order     查询排序
     * @param  integer $page      页数
     * @param  integer $page_size 每页条数
     * @return array              返回查询数据
     */
    public function lists($map = array(), $field = '', $order = '', $page = 0, $page_size = 10)
    {
        $list = $this->_list($map, $fields, $order, $page, $page_size);

        if (empty($list)) {
            return array();
        }

        $user_id = array_column($list, 'user_id');
        $to_user_id = array_column($list, 'to_user_id');
        $user_id = array_merge($user_id, $to_user_id);

        $user_id = array_unique($user_id);

        $user_map['id'] = array('in', $user_id);
        $user_fields = 'id as user_id,username,name';
        $user_list = D('User')->_list($user_map, $user_fields);
        $user_list = array_column($user_list, null, 'user_id');

        foreach ($list as $_k => $_v) {
            $list[$_k]['user_username'] = $user_list[$_v['user_id']]['username'];
            $list[$_k]['user_name'] = $user_list[$_v['user_id']]['name'];
            $list[$_k]['to_user_username'] = $user_list[$_v['to_user_id']]['username'];
            $list[$_k]['to_user_name'] = $user_list[$_v['to_user_id']]['name'];
        }
        return $list;
    }
}

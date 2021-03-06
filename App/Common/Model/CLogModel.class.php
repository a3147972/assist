<?php
namespace Common\Model;

use Common\Model\BaseModel;
use Common\Tools\ArrayHelper;

class CLogModel extends BaseModel
{
    protected $tableName = 'c_log';
    protected $selectFields = 'id, user_id, type, money, source, desc, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择操作的会员', 1),
        array('type', 'require', '请选择操作类型', 1),
        array('money', 'require', '请输入操作金额', 1),
        array('source', 'require', '请选择操作来源', 1),
        array('desc', 'require', '请输入操作描述', 1),
    );
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 写入奖励钱包记录
     * @param  int  $user_id 会员id
     * @param  integer $type    操作类型 1-增加 2-减少
     * @param  integer $money   操作金额
     * @param  integer $source  操作来源 来源 1-推荐奖励 2-管理奖励 3-获取收益 4-拉黑扣除 5-冻结扣除 6-充值
     * @param  string  $desc    操作描述
     * @return bool           成功返回true,失败返回false
     */
    public function insert($user_id, $type = 1, $money = 0, $source = 1, $desc = '')
    {
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['money'] = $money;
        $data['source'] = $source;
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
     * 查询奖金钱包记录
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
        //合并数据
        foreach ($list as $_k => $_v) {
            $list[$_k] = array_merge($_v, $user_list[$_v['user_id']]);
        }

        return $list;
    }
}

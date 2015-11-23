<?php
namespace Common\Model;

use Common\Model\BaseModel;
use Common\Tools\ArrayHelper;

class AssistModel extends BaseModel
{
    protected $tableName = 'assist';
    protected $selectFields = 'id,user_id,money,surplus_money,status,create_time';

    /**
     * 写入援助记录
     * @param  int $user_id 会员id
     * @param  int $money   援助金额
     * @return bool          成功返回true,失败返回false
     */
    public function insert($user_id, $money)
    {
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['match_money'] = 0;
        $data['status'] = 0;
        $data['create_time'] = time();
        $data['modify_time'] = time();

        $result = $this->add($data);

        if ($result) {
            return true;
        }

        return false;
    }

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

    /**
     * 匹配援助金额
     * @param  array $assist_list 援助金额数组,array('assist_id'=>'','money'=>'','status'=>'')
     * @return bool               更新成功返回true,失败返回false
     */
    public function matchMoney($assist_list)
    {
        $data = array();
        foreach ($assist_id_list as $_k => $_v) {
            $_data['id'] = $_v['assist_id'];
            $_data['match_money'] = array('exp', 'match_money + ' . $_v['money']);
            $_data['status'] = $_v['status'];
            array_push($data, $_data);
        }

        $result = $this->addAll($data, array(), true);

        if ($result) {
            return ture;
        }
        return false;
    }

    /**
     * 获取当月排队次数
     */
    public function getMonthCount($user_id, $month = '')
    {
        $month = empty($month) ? date('m') : $month;
        $map['user_id'] = $user_id;
        $map['from_unixtime(`create_time`,"%m")'] = $month;

        $count = $this->where($map)->count();
        return $count;
    }

    /**
     * 获取当天排队次数
     * @param  int $user_id 会员id
     * @param  string $day     天
     * @return int          次数
     */
    public function getDayCount($user_id, $day = '')
    {
        $month = empty($month) ? date('d') : $month;
        $map['user_id'] = $user_id;
        $map['from_unixtime(`create_time`,"%d")'] = $day;

        $count = $this->where($map)->count();
        return $count;
    }
}

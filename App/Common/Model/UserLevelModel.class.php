<?php
namespace Common\Model;

use Common\Model\BaseModel;

class UserLevelModel extends BaseModel
{
    protected $tableName = 'user_level';
    protected $selectFields = 'id, name, queue_max_time_day, queque_max_time_month, queque_min_time_month, recommend_reward, manage_reward, earnings_max_7_day, upgrade_count,black_c_penalty,black_c_penalty,freeze_c_penalty,freeze_r_penalty,freeze_penalty,create_time';

    protected $_validate = array(
        array('name', 'require', '请输入会员级别名称', 1),
        array('name', '', '会员级别已经存在！', 0, 'unique', 1),
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('modify_time', 'time', 3, 'function'),
    );



    /**
     * 获取上级级别id
     */
    public function getSuperior($level_id)
    {
        //获取上级级别id
        $map['id'] = array('gt', $level_id);
        $superior = $this->_get($map, '', 'id asc');

        if ($superior) {
            return $superior;
        } else {
            return false;
        }
    }
}

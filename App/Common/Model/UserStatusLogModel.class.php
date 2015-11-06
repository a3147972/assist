<?php
namespace Common\Model;

use Common\Model\BaseModel;

class UserStatusLogModel extends BaseModel
{
    protected $tableName = 'user_status_log';
    protected $selectFields = 'id, user_id, status, money, desc, create_time';

    protected $_validate = array(
        array('user_id', 'require', '请选择要操作的会员', 1),
        array('status', 'require', '请选择要执行的操作', 1),
        array('desc', 'require', '请输入操作描述', 1),
    );

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 更改会员状态
     *
     * @param  integer  $user_id 会员id
     * @param  integer $status  状态 1-正常 0-禁用 2-冻结 3-拉黑
     * @param  string  $desc    描述
     * @return bool             成功返回true,失败返回false
     */
    public function changeStatus($user_id, $status = 1, $desc = '')
    {
        $UserModel = D('User');
        $user_info = $UserModel->getField($user_id, 'status,level_id');

        if ($user_info['status'] == $status) {
            return true;
        }
        //获取罚金
        $LevelModel = D('UserLevel');
        $level_map['id'] = $user_info['level_id'];
        switch ($status) {
            case 2:
                $penalty = $LevelModel->where($level_map)->getField('freeze_penalty');
                break;
            case 3:
                $penalty = $LevelModel->where($level_map)->getField('black_penalty');
                break;
            default :
                $penalty = 0;
        }

        $data['user_id'] = $user_id;
        $data['status'] = $desc;
        $data['money'] = $penalty;
        $data['desc'] = $desc;

        if (!$this->create($data)) {
            return false;
        }

        $user_map['user_id'] = $user_id;
        $this->startTrans();
        $result = $UserModel->where($user_map)->setField('status', $status);
        $add_status = $this->add();

        if ($result !== false && $add_status !== false) {
            $this->commit();
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }
}

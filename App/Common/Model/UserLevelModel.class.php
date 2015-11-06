<?php
namespace Common\Model;

use Common\BaseModel;

class UserLevelModel extends BaseModel
{
    protected $tableName = 'user_level';
    protected $selectFields = 'id, name, queue_max_time_day, queque_max_time_month, queque_min_time_month, recommend_reward, manage_reward, earnings_max_7_day, create_time';
}

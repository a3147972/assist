<?php
namespace Common\Model;

use Common\Model\BaseModel;

class AboutModel extends BaseModel
{
    protected $tableName = 'about';
    protected $selectFields = 'id, title, info, content, create_time';

    protected $_validate = array(
        array('title', 'require', '请输入公告标题', 1),
        array('content', 'require', '请输入公告内容', 1),
    );
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('modify_time', 'time', 3, 'function'),
    );
}

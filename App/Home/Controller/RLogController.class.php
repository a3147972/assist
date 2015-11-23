<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class RLogController extends BaseController
{
    public function _filter()
    {
        $map['user_id'] = session('user_info.id');

        return $map;
    }
}

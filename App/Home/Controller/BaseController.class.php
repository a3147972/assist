<?php
namespace Home\Controller;

use Common\Tools\Page;
use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        if (!session('user_info.id')) {
            redirect(U('Login/index'));
            exit();
        }
        //判断排队次数
        $this->checkQueueCount();
    }

    public function index()
    {
        $page_index = I('page', 1);
        $page_size = 10;

        $model = D(CONTROLLER_NAME);
        $map = method_exists($this, '_filter') ? $this->_filter() : array();
        if (method_exists($model, 'lists')) {
            $list = $model->lists($map, '', 'id desc', $page_index, $page_size);
        } else {
            $list = $model->_list($map, '', 'id desc', $page_index, $page_size);
        }

        $count = $model->_count($map);

        //分页数组
        $page = new Page($count, $page_index, $page_size);
        $page_list = $page->show();

        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page_list', $page_list);
        $this->display();
    }

    /**
     * 判断排队次数
     */
    public function checkQueueCount()
    {
        //已排队次数
        $count = D('Assist')->getMonthCount(session('user_info.id'));
        //当月最后两天少于排队次数则给出提示
        if ((date('t') - date('d')) <= 2 && session('level_info.queue_min_time_month') > $count) {
            $this->assign('您当前排队次数:' . $count . ',少于' . session('level_info.queue_min_time_month') . '请完成排队任务已保证不被冻结');
        }
        //判断上个月的排队次数并且未被冻结过则冻结账号
        $prevMonth = date('m', strtotime('-1 month', time()));
        $prevMonthCount = D('Assist')->getMonthCount(session('user_info.id'), $prevMonth);
        //上个月是否因为排队次数被冻结
        $status_map['from_unixtime(`create_time`,"%m")'] = $prevMonth;
        $status_map['id'] = session('user_info.id');
        $status_map['desc'] = '未满足最大排队次数被冻结';
        $prevMonthUserStatus = D('UserStatusLog')->_get($status_map, '', 'id desc');

        //未被冻结且排队次数少于指定次数
        if (!$prevMonthUserStatus && $prevMonthCount < session('level_info.queque_min_time_month')) {
            $model = D('User');

            $this->startTrans();
            $freeze_result = $model->changeStatus(session('user_info.id'), -2);
            $status_log_result = D('UserStatusLog')->insert(session('user_info.id'), -2, '未满足最大排队次数被冻结');

            if ($freeze_result && $status_log_result) {
                $model->commit();
            } else {
                $model->rollback();
            }
        }
    }

    /**
     * 判断级别升级
     */
    public function checkUpgrade()
    {
        //获取当前级别的下级
        $lower_map['id'] = array('lt', session('level_info.id'));
        $lowerLevel = D('UserLevel')->_get($lower_map, '', 'id desc');
        //获取当前会员的直线下线数量
        $lower_user_map['pid'] = session('user_info.user_id');
        $lower_user_map['level_id'] = empty($lowerLevel) ? session('level_info.id') : $lowerLevel['id'];
        $lower_user_list = D('User')->_list(array('pid' => session('user_info.user_id')));
        if (count($lower_user_list) >= session('level_info.upgrade_count')) {
            $lower_user_id = array_column($lower_user_list, 'id');
            $lower_order_count = D('Order')->where(array('in', $lower_user_id))->count();
            if ($lower_order_count == count($lower_user_list)) {
                //升级
                $upgrade_result = D('User')->Upgrade(session('user_info.user_id'));
                if ($upgrade_result) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return true;
    }
}

<?php
namespace Admin\Contorller;

use Admin\Controller\BaseModel;

class ReportController extends BaseModel
{
    /**
     * 举报审核通过操作
     * 更改举报记录状态
     * 并且更改被举报人状态为拉黑
     * 更改被举报人上级状态为冻结
     * @return [type] [description]
     */
    public function pass()
    {
        $id = I('id');

        $model = D('Report');
        $info = $model->_get(array('id' => $id)); //获取举报记录状态
        $user_info = D('User')->_get(array('id' => $info['to_user_id'])); //查询被举报人信息
        $model->startTrans();
        //更改举报记录状态
        $result = $model->changeStatus($id, 1);
        //拉黑被举报人
        $$black_result = D('User')->changeStatus($info['to_user_id'], 3);
        $add_black_log = D('UserStatusLog')->insert($info['to_user_id'], 3, '举报拉黑');
        //冻结被举报人上级
        if ($user_info['pid'] != 0) {
            $add_freeze_log = D('UserStatusLog')->changeStatus($user_info['pid'], 2, '下级会员' . $user_info['username'] . '被举报冻结');
            $freeze_result = D('User')->changeStatus($user_info['pid'], 2);
        } else {
            $freeze_result = true;
            $add_freeze_log = true;
        }

        if ($result == false) {
            $model->rollback();
            $this->error('更改举报记录状态失败');
        }
        if ($black_result == false) {
            $model->rollback();
            $this->error('拉黑被举报人失败');
        }
        if ($freeze_result == false) {
            $model->rollback();
            $this->error('冻结被举报人上级失败');
        }
        if ($add_freeze_log == false || $add_black_log == false) {
            $model->rollback();
            $this->error('写入状态日志记录失败');
        }

        $model->commit();
        $this->success('操作成功', U('Report/index'));
    }

    public function close()
    {
        $id = I('id');
        $model = D('Report');
        $map['id'] = $id;
        $result = $model->where($map)->setField('status', -1);

        if ($result) {
            $this->success('操作成功', U('Report/index'));
        } else {
            $this->error('操作失败');
        }
    }
}

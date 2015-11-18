<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class LetterController extends BaseController
{
    public function send()
    {
        if (IS_POST) {
            $user_id = session('user_info.id');
            $to_user_id = 0;
            $title = I('post.title');
            $content = I('post.content');

            $result = D('Letter')->send($user_id, $to_user_id, $title, $content);

            if ($result) {
                $this->success('发送成功');
            } else {
                $this->error('发送失败');
            }
        } else {
            $this->display();
        }
    }
    /**
     * 回复站内信
     */
    public function reply()
    {
        if (IS_POST) {
            $model = D('Letter');

            $title = I('post.title');
            $content = I('post.content');
            $to_user_id = I('post.to_user_id');

            $result = $model->send(0, $to_user_id, $title, $content);

            if ($result) {
                $this->success('回复成功', U('Letter/index'));
            } else {
                $this->error($model->getError());
            }
        } else {
            $model = D('Letter');

            $map['id'] = I('id');

            $info = $model->get($map);

            $this->assign('vo', $info);
            $this->display();
        }
    }
}

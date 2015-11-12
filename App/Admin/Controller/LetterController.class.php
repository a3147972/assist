<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;

class LetterController extends BaseController
{
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

    /**
     * 查看站内信
     */
    public function view()
    {
        $model = D('Letter');

        $map['id'] = I('id');

        $info = $model->get($map);

        $this->assign('vo', $info);
        $this->display();
    }
}

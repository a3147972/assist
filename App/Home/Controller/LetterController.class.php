<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class LetterController extends BaseController
{
    public function _filter()
    {
        $map['user_id'] = session('user_info.id');

        return $map;
    }
    /**
     * 发件箱
     */
    public function out()
    {
        $page_index = I('page', 1);
        $page_size = 10;

        $model = D('Letter');
        $map['to_user_id'] = session('user_info.id');
        $list = $model->lists($map, '', 'id desc', $page_index, $page_size);
        $count = $model->count();

        //分页数组
        $page = new Page($count, $page_index, $page_size);
        $page_list = $page->show();

        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page_list', $page_list);
        $this->display();
    }
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

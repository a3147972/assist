<?php
namespace Home\Controller;

use Common\Tools\Upload;
use Home\Controller\BaseController;

class PublicController extends BaseController
{
    public function AjaxUpload()
    {
        $upload = new Upload();

        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = '';
        $rootPath = './Uploads/';
        $upload->rootPath = $rootPath;
        $info = $upload->upload();

        if ($info) {
            $fileinfo = array_shift($info);
            $filename = $rootPath . $fileinfo['savepath'] . $fileinfo['savename'];
            $data['status'] = 1;
            $data['info'] = $filename;
        } else {
            $data['status'] = 0;
            $data['info'] = $upload->getError();
        }
        die(json_encode($data));
    }
}

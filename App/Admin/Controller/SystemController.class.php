<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;

class SystemController extends BaseController
{
    public function index()
    {
        $this->display();
    }

    /**
     * 写入配置文件
     * @method insert
     */
    public function insert()
    {
        $path = './App/Common/Conf/site.php';
        $config = var_export(I('post.'), true);

        $str = "<?php\n";
        $str .= "return " . $config . ';';

        $save_result = file_put_contents($path, $str);

        if ($save_result) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败,请检查权限');
        }
    }
}

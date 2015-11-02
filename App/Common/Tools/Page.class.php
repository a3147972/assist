<?php
/**
 * 分页类方法，返回分页数组
 *
 * @package Tools
 * @author guolei <2387813033@qq.com>
 */
namespace Common\Tools;

class Page
{
    private $page = 1; //当前页
    private $page_size = 10; //每页条数
    private $page_roll = 5; //显示页数

    public function __construct($total, $page = 1, $page_size = 10, $page_roll = 5)
    {
        $this->page = $page;
        $this->total = $total;
        $this->page_size = $page_size;
        $this->page_roll = $page_roll;
    }

    public function show()
    {
        $page_list = array();
        $page_total = intval(ceil($this->total / $this->page_size));

        //将首页写入数组
        $page_first['name'] = 'first';
        $page_first['page'] = 1;
        if ($this->page <= $this->page_roll) {
            $page_first['status'] = 'disabled';
        }
        array_push($page_list, $page_first);

        //将第一页写入数组
        $page_prev['name'] = 'prev';
        $page_prev['page'] = $this->page - 1 <= 0 ? 1 : $this->page - 1;
        if ($this->page <= $this->page_roll) {
            $page_prev['status'] = 'disabled';
        }
        array_push($page_list, $page_prev);

        //写入分页列表
        $now_max_page = ($this->page_roll + $this->page - 1) > $page_total ? $page_total : $this->page_roll + $this->page - 1;

        $i = $this->page - intval($this->page_roll / 2);

        $i = $i <= 0 ? 1 : $i;
        $now_max_page = $i + $this->page_roll - 1;
        $now_max_page = $now_max_page > $page_total ? $page_total : $now_max_page;

        for ($i; $i <= $now_max_page; $i++) {
            $page['name'] = 'list';
            $page['page'] = $i;

            if (intval($this->page) === intval($i)) {
                $page['status'] = 'now';
            } else {
                unset($page['status']);
            }
            array_push($page_list, $page);
        }

        //将下一页写入数组
        $page_next['name'] = 'next';
        $page_next['page'] = $this->page + 1 >= $page_total ? $page_total : $this->page + 1;
        if ($this->page > $page_total - $this->page_roll) {
            $page_next['status'] = 'disabled';
        }
        array_push($page_list, $page_next);

        //将最后一页写入数组
        $page_last['name'] = 'last';
        $page_last['page'] = $page_total;
        if ($this->page > $page_total - $this->page_roll) {
            $page_last['status'] = 'disabled';
        }
        array_push($page_list, $page_last);

        return $page_list;
    }
}

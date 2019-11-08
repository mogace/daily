<?php
/**
 * 分页类
 * @autor mogace
 * @date 2019/11/08
 * @url
 */
class Page{
    private $total = 0; // 总数量
    private $page = 1; // 当前页
    private $pageSize; // 分页大小
    private $pageCount; // 总页数

    public function __construct($total, $pageSize = 10){
        $this->total = $total;
        $this->pageSize = $pageSize;
        $this->pageCount = ceil($this->total / $this-> pageSize);
    }

    public function sizePage(){ // 获取页码
        $page = '';
        for ($i= 1; $i <= $this->pageCount; $i++) {
            $page .= $this->getLinks($i);
        }

        return $page;
    }

    public function getLinks($page){
        return '<a href="?page="'.$page.'>'.$page.'</a>';
    }

    public function render(){ // 渲染分页
        echo $this->sizePage();
    }
}
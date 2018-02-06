<?php
/**
 * file: 后台管理员页面
 */

// 引入命名空间
namespace Admin\Controller;

// 引入命名空间
use Common\Helper;

class MenuController extends Controller
{
    // 定义查询数据
    public $model = 'menu';
    public function  where($params)
    {
        return [
            'id'      => 'eq',
            'url'     => 'like',
            'status'  => 'eq',
        ];
    }

    // 首页显示处理
    public function index()
    {
        // 查询主栏目
        $parent = M($this->model)->field('id, menu_name')->where(['status' => 1, 'pid' => 0])->order('sort')->select(['index' => 'id']);
        if ( ! empty($parent)) $parent = Helper::map($parent, 'id', 'menu_name');
        $parent[0] = '父级分类';
        $this->assign('parents', $parent);
        $this->display('index');
    }
}
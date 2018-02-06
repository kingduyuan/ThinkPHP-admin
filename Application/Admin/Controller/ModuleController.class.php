<?php
/**
 * file: 后台管理员页面
 */

// 引入命名空间
namespace Admin\Controller;

use Common\Auth;
use Think\Model;

// 引入命名空间
class ModuleController extends Controller
{
    // 第一步接收标题和数据表数据生成表单配置信息
    public function create()
    {
        // 接收参数
        $strTitle = post('title'); // 标题
        $strTable = post('table'); // 数据库表
        if ( ! empty($strTable) && ! empty($strTitle))
        {
            // 获取表信息
            $this->arrError['msg'] = '数据表不存在';
            $model  = new Model();
            $tables = $model->query('SHOW TABLES');
            if ($tables)
            {
                $isHave   = false;
                foreach ($tables as $value)
                {
                    if (in_array($strTable, $value))
                    {
                        $isHave = true;
                        break;
                    }
                }

                if ($isHave)
                {
                    // 查询表结构信息
                    $arrTables = $model->query('SHOW FULL COLUMNS FROM `'.$strTable.'`');
                    $this->arrError['msg'] = '查询表结构失败';
                    if ($arrTables)
                    {
                        // 成功返回
                        $this->arrError = [
                            'status' => 1,
                            'msg'    => '生成预览表单成功',
                            'data'   => $this->createForm($arrTables),
                        ];
                    }
                }
            }


        }

        $this->ajaxReturn();
    }

    // 第二步生成预览HTML文件
    public function update()
    {
        $attr  = post('attr');
        $table = post('table');
        if ($attr)
        {
            $this->arrError['msg'] = '对应数据库表不存在';
            if ($table && ($name = trim($table, 'my_')))
            {
                // 拼接字符串
                $dirName  = APP_PATH.'Admin/';
                $strCName = ucfirst($name).'Controller.class.php';
                $strVName = $name.'.html';

                // 返回数据
                $this->arrError = [
                    'status' => 1,
                    'msg'    => '生成预览文件成功',
                    'data'   => [
                        'html'       => highlight_string($this->createHtml($attr, post('title')), true),
                        'file'       => [$strVName, file_exists($dirName.'View/Admin/'.$strVName)],
                        'controller' => [$strCName, file_exists($dirName.'Controller/'.$strCName)],
                    ],
                ];
            }
        }

        $this->ajaxReturn();
    }

    // 第三步开始生成文件
    public function produce()
    {
        // 接收参数
        $attr  = post('attr');       // 表单信息
        $table = post('table');      // 操作表
        $title = post('title');      // 标题信息
        $html  = post('html');       // HTML 文件名
        $php   = post('php');        // PHP  文件名
        $auth  = (int)post('auth');  // 生成权限
        $menu  = (int)post('menu');  // 生成导航
        $allow = (int)post('allow'); // 允许文件覆盖

        if ($attr && $table && $title && $html && $php)
        {
            $this->arrError['msg'] = '对应数据库表不存在';
            if ($table && ($name = trim($table, 'my_')))
            {
                // 拼接字符串
                $dirName  = APP_PATH.'Admin/';
                $strCName = $dirName.'Controller/'.(stripos($php, '.class.php') ? $php : $php.'.class.php');
                $strVName = $dirName.'View/Admin/'.(stripos($html, '.html') ? $html : $html.'.html');

                // 验证文件不存在
                $this->arrError['msg'] = '文件存在, 不能执行覆盖操作';
                if ($allow === 1 ||  (! file_exists($strCName) && ! file_exists($strVName)))
                {
                    // 生成权限
                    if ($auth == 1) $this->createAuth($name, $title);

                    // 生成导航栏目
                    if ($menu == 1) $this->createMenu($name, $title);

                    // 生成HTML
                    $strWhere = $this->createHtml($attr, $title, $strVName);

                    // 生成控制器
                    $this->createController($name, $title, $strCName, $strWhere);

                    // 返回数据
                    $this->arrError = [
                        'status' => 1,
                        'msg'    => '生成预览文件成功',
                        'data'   => '/admin/'.$name.'/index.html',
                    ];
                }


            }
        }

        $this->ajaxReturn();
    }

    /**
     * createAuth()生成权限操作
     * @access private
     * @param  string $prefix 前缀名称
     * @param  string $title  标题
     * @return void
     */
    private function createAuth($prefix, $title)
    {
        $prefix = '/'.trim($prefix, '/').'/';
        $auth = ['index' => '显示', 'search' => '搜索', 'update' => '编辑'];
        foreach ($auth as $key => $value) Auth::createItem('/admin'.$prefix.$key, $title.$value, Auth::AUTH_TYPE);
    }

    /**
     * createMenu() 生成导航栏信息
     * @access private
     * @param  string $name  权限名称
     * @param  string $title 导航栏目标题
     * @return void
     */
    private function createMenu($name, $title)
    {
        $model = M('menu');
        if ( ! $model->where(['menu_name'])->find())
        {
            $time = time();
            $model->add([
                'menu_name'   => $title,
                'pid'         => 0,
                'icons'       => 'icon-cog',
                'url'         => '/admin/'.$name.'/index',
                'status'      => 1,
                'create_time' => $time,
                'create_id'   => $this->user->id,
                'update_time' => $time,
                'update_id'   => $this->user->id,
            ]);
        }

    }

    /**
     * createForm() 生成表格配置表单信息
     * @access private
     * @param  array  $array  数据表信息
     * return  string 返回HTML
     */
    private function createForm($array)
    {
        $strHtml = '<div class="alert alert-info">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <strong>填写配置表格信息!</strong>
</div>';
        foreach ($array as $value)
        {
            $key     = $value['field'];
            $sTitle  = isset($value['comment']) && ! empty($value['comment']) ? $value['comment'] : $value['field'];
            $sOption = isset($value['null']) && $value['null'] == 'NO' ? '"required":true,' : '';
            if (stripos($value['type'], 'int(') !== false) $sOption .= '"number":true,';
            if (stripos($value['type'], 'varchar(') !== false) {
                $sLen = trim(str_replace('varchar(', '', $value['type']), ')');
                $sOption .= '"rangelength":"[2, '.$sLen.']"';
            }

            $sOther = stripos($value['field'], 'time') !== false ? 'dateTimeString' : '';

            $strHtml .= <<<HTML
<div class="alert alert-success me-alert-su">
    <span class="label label-success me-label-sp">{$key}</span>
    <label class="me-label">标题: <input type="text" name="attr[{$key}][title]" value="{$sTitle}" required="true"/></label>
    <label class="me-label">编辑：
        <select class="is-hide" name="attr[{$key}][edit]">
            <option value="1" selected="selected">开启</option>
            <option value="0" >关闭</option>
        </select>
        <select name="attr[{$key}][type]">
            <option value="text" selected="selected">text</option>
            <option value="hidden">hidden</option>
            <option value="select">select</option>
            <option value="radio">radio</option>
            <option value="password">password</option>
            <option value="textarea">textarea</option>
        </select>
        <input type="text" name="attr[{$key}][options]" value='{$sOption}'/>
    </label>
    <label class="me-label">搜索：
        <select name="attr[{$key}][search]">
            <option value="1">开启</option>
            <option value="0" selected="selected">关闭</option>
        </select>
    </label>
    <label class="me-label">排序：<select name="attr[{$key}][bSortable]">
        <option value="1" selected="selected">开启</option>
        <option value="0" >关闭</option>
    </select></label>
    <label class="me-label">回调：<input type="text" name="attr[{$key}][createdCell]" value="{$sOther}" /></label>
</div>
HTML;
        }

        return $strHtml;
    }

    /**
     * createHtml() 生成预览HTML文件
     * @access private
     * @param  array  $array 接收表单配置文件
     * @param  string $title 标题信息
     * @param  string $path  文件地址
     * @return string 返回 字符串
     */
    private function createHtml($array, $title, $path = '')
    {
        $strHtml = $strWhere =  '';
        if ($array)
        {
            foreach ($array as $key => $value)
            {
                $html = "\t\t\t{\"title\": \"{$value['title']}\", \"data\": \"{$key}\", \"sName\": \"{$key}\", ";

                // 编辑
                if ($value['edit'] == 1) $html .= "\"edit\": {\"type\": \"{$value['type']}\", \"options\": {{$value['options']}}}, ";

                // 搜索
                if ($value['search'] == 1)
                {
                    $html     .= "\"search\": {\"type\": \"text\"}, ";
                    $strWhere .= "\t\t\t'{$key}' => 'eq', \n";
                }

                // 排序
                if ($value['bSortable'] == 0) $html .= '"bSortable": false, ';

                // 回调
                if (!empty($value['createdCell'])) $html .= '"createdCell" : '.$value['createdCell'].', ';

                $strHtml .= trim($html, ', ')."}, \n";
            }

            $strHtml .= "\t\t\toOperate";
        }

        $sHtml =  <<<html
<!--前面导航信息-->
<div class="box-header" data-original-title="">
    <h2><i class="icon-desktop"></i><span class="break"></span></h2>
    <div class="box-icon">
        <a title="导出" class="me-table-export"><i class="icon-download-alt"></i></a>
        <a title="添加" class="me-table-insert"><i class="icon-plus"></i></a>
        <a title="刷新" class="me-table-reload" id="table-refresh"  ><i class="icon-refresh"></i></a>
        <a title="全屏" id="toggle-fullscreen" class="hidden-phone hidden-tablet"  href="#"><i class="icon-fullscreen"></i></a>
        <a class="btn-minimize" title="隐藏" href="#"><i class="icon-chevron-up"></i></a>
        <a class="btn-close" title="删除" href="#"><i class="icon-remove"></i></a>
    </div>
</div>
<div class="box-content">
    <!--表格数据-->
    <table class="table table-striped table-bordered table-hover" id="showTable"></table>
</div>
<script type="text/javascript">
    var myTable = new MeTable({sTitle:"{$title}"},{
        "aoColumns":[
{$strHtml}
        ],

        // 设置隐藏和排序信息
        // "order":[[0, "desc"]],
        // "columnDefs":[{"targets":[2,3], "visible":false}],
    });

    /**
     * 显示的前置和后置操作
     * myTable.beforeShow(object data, bool isDetail) return true 前置
     * myTable.afterShow(object data, bool isDetail)  return true 后置
     */

     /**
      * 编辑的前置和后置操作
      * myTable.beforeSave(object data) return true 前置
      * myTable.afterSave(object data)  return true 后置
      */

    $(function(){
        myTable.init();
    })
</script>
html;
        // 生成文件
        if ( ! empty($path))
        {
            file_put_contents($path, $sHtml);
            return $strWhere;
        }

        return $sHtml;
    }

    /**
     * createController()生成控制器文件
     * @access private
     * @param  string $name  控制器名
     * @param  string $title 标题
     * @param  string $path  文件名
     * @return void
     */
    private function createController($name, $title, $path, $where)
    {
        $strFile = trim(strrchr($path, '/'), '/');
        $strDate = date('Y-m-d H:i:s');
        $strName = trim($strFile, '.class.php');
        $strControllers = <<<Html
<?php
/**
 * file: {$strFile}
 * desc: {$title} 执行操作控制器
 * user: liujx
 * date: {$strDate}
 */

// 引入命名空间
namespace Admin\Controller;

class {$strName} extends Controller
{
    // model
    protected \$model = '{$name}';

    // 查询方法
    public function where(\$params)
    {
        return [
            {$where}
        ];
    }

    // 新增之前的处理
    protected function beforeInsert(&\$model)
    {
        \$model->update_id   = \$model->create_id   = \$this->user->id;
        \$model->update_time = \$model->create_time = time();
        return true;
    }

    // 修改之前的处理
    protected function beforeUpdate(&\$model)
    {
        \$model->update_id   = \$this->user->id;
        \$model->update_time = time();
        return true;
    }
}
Html;

        file_put_contents($path, $strControllers);
    }
}
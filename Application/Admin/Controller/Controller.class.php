<?php
/**
 * file: Controller.class.php
 * desc: 后台公共控制器
 * user: liujx
 * date: 2016-3-12
 */

// 定义命名空间
namespace Admin\Controller;

// 引入命名空间
use Common\Auth;

class Controller extends \Common\Controller
{
    // 定义验证数据、模型、主键
    protected $validate = [], $model = 'admin', $pk = 'id', $thumb = [160, 160], $sort = 'id';

    /**
     * where() 查询方法
     * @access protected
     * @param  array $params 页面提交过来的查询条件信息
     * @return array 返回查询数据信息(默认没有查询条件)
     */
    protected function where($params)
    {
        return [];
    }

    // 初始化判断定义
    public function _initialize()
    {
        // 判断是否已经登录
        parent::_initialize();
        
        // 不是管理员需要验证权限
        if ($this->user->id !== 1) {
            // 验证用户权限
            if ( ! Auth::can($this->user->id, strtolower('/'.MODULE_NAME.'/'.CONTROLLER_NAME).'/'.ACTION_NAME)) {
                $strMsg = '抱歉！你没有执行权限 :)';
                IS_AJAX ? $this->ajaxReturn($strMsg) : $this->go($strMsg);
            }
        }
    }

    // 获取数据页面
    public function index()
    {
        $this->display();
    }

    // 查询处理
    protected function query()
    {
        // 接收参数
        $aParams = post('params');                   // 查询参数
        $sOrder  = post('sSortDir_0', 'desc');       // 排序类型
        $aWhere  = $this->where($aParams);           // 查询条件信息
        $sFile   = isset($aParams['orderBy']) && ! empty($aParams['orderBy']) ? $aParams['orderBy'] : $this->sort; // 排序字段
        $aSearch = [
            'orderBy' => [$sFile => $sOrder],
            'where'   => [],                         // 查询条件
        ];

        // 自定义了排序
        if ( ! empty($aWhere) && isset($aWhere['orderBy']) && ! empty($aWhere['orderBy']))
        {
            // 判断自定义排序字段还是方式
            $aSearch['orderBy'] = is_array($aWhere['orderBy']) ? $aSearch['orderBy'] : [$aSearch['orderBy'] => $sOrder];
            unset($aWhere['orderBy']);
        }

        // 处理默认查询条件
        if ( ! empty($aWhere) && isset($aWhere['where']) && ! empty($aWhere['where']))
        {
            $aSearch['where'] = array_merge($aSearch['where'], $aWhere['where']);
            unset($aWhere['where']);
        }

        // 处理其他查询条件
        if ( ! empty($aParams) && ! empty($aWhere))
        {
            foreach ($aParams as $key => $value)
            {
                if (! isset($aWhere[$key])) continue;
                $tmpKey = $aWhere[$key];
                if (is_array($tmpKey))
                    $aSearch['where'][$key] = $tmpKey;
                else
                {
                    if ($tmpKey == 'like') $value = "%{$value}%";
                    $aSearch['where'][$key] = [$tmpKey, $value];
                }
            }
        }

        return $aSearch;
    }

    // 查询所有数据
    public function search()
    {
        if (IS_AJAX)
        {
            $model   = M($this->model);
            // 接收参数
            $intNum  = (int)post('sEcho');               // 第几页
            $start   = (int)post('iDisplayStart',  0);   // 开始位置
            $length  = (int)post('iDisplayLength', 10);  // 查询长度
            $aSearch = $this->query();

            // 查询数据
            $count = $model->where($aSearch['where'])->count();
            $data  = $model->where($aSearch['where'])->limit($start, $length)->order($aSearch['orderBy'])->select();
            // echo $model->getLastSql();
            $this->arrError = [
                'status' => 1,
                'msg'    => 'success',
                'data'   => [
                    'sEcho'                => $intNum,      // 请求次数
                    'iTotalRecords'        => count($data), // 当前页面条数
                    'iTotalDisplayRecords' => (int)$count,  // 数据总条数
                    'aaData'               => $data,        // 数据信息
                ],
            ];
        }

        $this->ajaxReturn();
    }

    // 修改数据
    public function update()
    {
        if (IS_AJAX)
        {
            // 接收参数
            $type    = post('actionType');                  // 操作类型
            $arrType = ['delete', 'insert', 'update'];      // 可执行操作
            $this->arrError['msg'] = "操作类型错误";

            // 操作类型判断
            if (in_array($type, $arrType, true))
            {
                // 数据验证
                $model  = D($this->model);

                // 修改和删除验证数据存在
                $this->arrError['msg'] = '将要操作的数据不存在';
                if ($type === 'insert' || ($data = $model->find(post($this->pk))))
                {
                    // 数据的验证
                    $isTrue = $model->validate($this->validate)->create();
                    $this->arrError['msg'] = $model->getError();
                    if ($isTrue || $type === 'delete')
                    {
                        $isTrue = false;
                        $this->arrError['msg'] = '服务器繁忙,请稍候再试...';

                        // 根据类型操作数据
                        switch($type)
                        {
                            case 'delete':
                                // 如果是管理员删除 验证权限 或者添加 者
                                $this->arrError['msg'] = '你没有权限操作';
                                if (CONTROLLER_NAME !== 'Admin' || $this->user->id === 1 || (Auth::can($this->user->id, 'deleteUser') && post('create_id') == $this->user->id))
                                {
                                    $this->arrError['msg'] = '服务器繁忙,请稍候再试...';
                                    if ($this->beforeDelete($model)) $isTrue = $model->delete();
                                }
                                break;
                            case 'update':
                                if ($this->beforeUpdate($model)) $isTrue = $model->save();
                                break;
                            case 'insert':
                                if ($this->beforeInsert($model) && ($isTrue = $model->add())) $data = $model->find($isTrue);
                                break;
                        }

                        $this->arrError['data'] = $model->getLastSql();
                        // 判断操作成功
                        if ($isTrue && $this->afterSave($data, post(), $type))
                        {
                            // 返回数据信息
                            $this->arrError = [
                                'status' => 1,
                                'msg'    => '恭喜您,操作成功 ^.^',
                                'data'   => $data,
                            ];
                        }
                    }
                }
            }
        }

        $this->ajaxReturn();
    }

    // 数据导出
    public function export()
    {
        // 接收参数
        $arrFields = post('aFields');       // 字段信息
        $intSize   = (int)post('iSize');    // 查询数据条数
        $strTitle  = post('sTitle');        // 标题信息

        // 数据验证
        if (IS_POST && $arrFields && $strTitle)
        {
            set_time_limit(0);
            // 查询信息
            $aSearch = $this->query();
            $arrKeys = array_keys($arrFields); // 所有的字段

            // 查询数据
            $objQuery = M($this->model)->field($arrKeys)->where($aSearch['where']);
            // if ($intSize > 0) $objQuery = $objQuery->limit($intSize);
            $data     = $objQuery->select();
            $this->arrError['msg'] = '没有需要导出的数据';
            if ($data)
            {
                // 1 引入phpExcel类
                import('Org.Util.PHPExcel'); // 没有命名空间使用 import 引入

                // 实例化一个phpExcel类
                $objPHPExcel = new \PHPExcel();

                $objPHPExcel->getProperties()->setCreator("Liujx Admin")
                ->setLastModifiedBy("Liujx Admin")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

                $objPHPExcel->setActiveSheetIndex(0);

                // 获取显示列的信息
                $intLength = count($arrFields);
                $arrLetter = range('A', 'Z');
                if ($intLength > 26)
                {
                    $arrLetters = array_slice($arrLetter, 0, $intLength - 26);
                    if ($arrLetters) foreach ($arrLetters as $value) array_push($arrLetter, 'A'.$value);
                }

                $arrLetter = array_slice($arrLetter, 0, $intLength);


                // 确定第一行信息
                foreach ($arrLetter as $key => $value)
                {
                    $objPHPExcel->getActiveSheet()->setCellValue($value.'1', $arrFields[$arrKeys[$key]]);
                }

                // 写入数据信息
                $intNum = 2;
                foreach ($data as $value)
                {
                    // 写入信息数据
                    foreach ($arrLetter as $intKey => $strValue)
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue($strValue.$intNum, $value[$arrKeys[$intKey]]);
                    }

                    $intNum ++;
                }


                // 设置sheet 标题信息
                $objPHPExcel->getActiveSheet()->setTitle($strTitle);
                $objPHPExcel->setActiveSheetIndex(0);

                // 设置头信息
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$strTitle.'.xlsx"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                // 直接输出文件
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
            }
        }

        $this->redirect('index');
    }

    /**
     * beforeUpload() 文件上传之前 对文件的一些验证
     * @access protected
     * @return object 返回上传对象
     */
    protected function beforeUpload()
    {
        // 接收上传文件信息
        $upload = new \Think\Upload();                     // 实例化上传文件类
        $upload->maxSize  = 1048576;                       // 上传文件大小
        $upload->exts     = ['jpg', 'gif', 'png', 'jpeg']; // 上传文件类型
        $upload->rootPath = './Public/Uploads/';           // 上传文件保存的根目录
        $upload->subName  = ['date', 'Ymd'];               // 上传保存子目录
        $upload->saveName = ['uniqid', CONTROLLER_NAME];   // 文件名称
        return $upload;
    }

    // 文件上传
    public function upload()
    {
        // 删除图片处理
        $strOldName = get('sOldName');
        if ($strOldName && file_exists('.'.$strOldName)) unlink('.'.$strOldName);

        // 获取上传对象
        $upload = $this->beforeUpload();

        // 文件上传
        $info = $upload->upload();
        $this->arrError['msg'] = $upload->getError();

        // 上传成功
        if ($info && $this->afterUpload($info))
        {
            $arrInfo = [];
            foreach ($info as $value)
            {
                $arrInfo[] = [
                    'sOldName' => $value['name'],                                           // 旧文件名
                    'sNewName' => $value['savename'],                                       // 新文件名
                    'sPath'    => trim($upload->rootPath, '.').$value['savepath'].$value['savename'], // 文件路径
                ];
            }

            $this->arrError = [
                'status' => 1,
                'msg'    => '文件上传成功',
                'data'   => $arrInfo,
            ];
        }

        $this->ajaxReturn();
    }

    // 图片裁剪
    public function clipping()
    {
        $intX    = (int)post('x');  // x轴
        $intY    = (int)post('y');  // y轴
        $intW    = (int)post('w');  // 宽度
        $intH    = (int)post('h');  // 高度
        $strPath = post('path');    // 图片路径
        if ($strPath && ($intX || $intY || $intW || $intH))
        {
            // 判读图片存在
            $this->arrError['msg'] = '处理图片不存在';
            $strPath = '.'. trim($strPath, '.');
            if (file_exists($strPath))
            {
                $image = new \Think\Image();
                $image->open($strPath);
                $this->arrError['msg'] = '图片裁剪失败';
                if ($image->crop($intW, $intH, $intX, $intY)->save($strPath))
                {
                    $image->open($strPath);
                    $this->arrError['msg'] = '图片缩放失败';
                    if ($image->thumb($this->thumb[0], $this->thumb[1], \Think\Image::IMAGE_THUMB_SCALE)->save($strPath))
                    {
                        $this->arrError = [
                            'status' => 1,
                            'msg'    => '图片裁剪成功',
                            'data'   => trim($strPath, '.'),
                        ];
                    }
                }
            }
        }
        $this->ajaxReturn();
    }

    // 新增之前的处理
    protected function beforeInsert(&$model)
    {
        $model->update_id   = $model->create_id   = $this->user->id;
        $model->update_time = $model->create_time = time();
        return true;
    }

    // 修改之前的处理
    protected function beforeUpdate(&$model)
    {
        $model->update_id   = $this->user->id;
        $model->update_time = time();
        return true;
    }

    // 删除之前的处理
    protected function beforeDelete(&$model) {return true;}

    /**
     * afterSave() 修改之后(新增\修改\删除)
     * @access protected
     * @param  array  $old  旧的数据
     * @param  array  $new  新的数据
     * @param  string $type 修改类型
     * @return bool
     */
    protected function afterSave($old, $new, $type) {return true;}

    /**
     * afterUpload() 上传文件之后的处理
     * @param  array $info 上传文件信息
     * @return bool  处理完成返回true
     */
    protected function afterUpload($info) {return true;}
}
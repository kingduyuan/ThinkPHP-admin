<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/3/15
 * Time: 17:20
 */
namespace Common;

class Controller extends \Think\Controller
{
    // 定义ajaxReturn 返回的数据
    public $arrError = [
        'status' => 0,              // 状态 1 成功 2 失败
        'msg'    => '提交数据为空',   // 提示信息
        'data'   => null,           // 返回数据
    ];

    // 定义session 的名称
    protected $_admin = 'my_admin';
    public    $user   = [];

    // 用户登录验证
    public function isLogin()
    {
        return isset($_SESSION[$this->_admin]) && isset($_SESSION[$this->_admin]['id']) && ! empty($_SESSION[$this->_admin]['id']);
    }

    // 初始化验证用户登录
    public function _initialize()
    {
        // 判断是否已经登录
        if (!$this->isLogin()) {
            if (IS_AJAX) {
                $this->arrError['msg'] = '请先登录...';
                $this->ajaxReturn();
            } else {
                $this->redirect('index/index');
            }
        }

        // 将用户信息转换为对象
        $this->user = (object)[
            'id'   => (int)$_SESSION[$this->_admin]['id'],  // 用户ID
            'name' => $_SESSION[$this->_admin]['username'], // 用户名
            'face' => $_SESSION[$this->_admin]['face'],     // 用户头像
        ];
    }

    // 图片上传
    public function fileUpload()
    {
        // 判断数据上传
        if (IS_POST)
        {
            $upload = new \Think\Upload();                          // 实例化上传类
            $upload->maxSize  = 1024 * 1024 * 2;                    // 上传文件大小
            $upload->rootPath = './public/';                        // 图片保存绝对路径
            $upload->autoSub  = true;
            $upload->exts     = array('jpg', 'gif', 'png', 'jpeg'); // 上传文件类型
            $upload->autoSub  = true;
            $upload->subName  = array('date','Ymd');                // 文件上传的子目录
            $info = $upload->upload();
            $this->arrMsg['msg'] = $upload->getError();

            // 上传成功
            if ($info)
            {
                // 获取上传图片信息
                $info = $info[$this->file];
                if ( ! isset($info['url']) || empty($info['url'])) $info['url'] = '/Public/'.$info['savepath'].$info['savename'];

                // 删除之前的图片
                $this->arrError = [
                    'status' => 1,
                    'msg'    => '上传成功',
                    'data'   => $info['url'],
                ];
            }
        }

        // 获取上传的Url
        $this->ajaxReturn();
    }

    /**
     * ajaxReturn()   重新父类的ajax返回数据
     * @access public
     * @param  string $message 提示信息
     * @param  mixed  $data    返回数据
     * @param  int    $status  返回状态
     * @return void   没有返回值
     */
    public function ajaxReturn($message = '', $data = [], $status = 0)
    {
        if (!empty($message)) $this->arrError['msg']    = $message;
        if (!empty($data))    $this->arrError['data']   = $data;
        if ($status === 1)    $this->arrError['status'] = 1;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($this->arrError));
    }

    /**
     * go执行跳转页面操作
     * @access protected
     * @param  string $message 提示信息
     * @param  int    $type    类型 0 失败 1 成功
     * @param  array  $param   其他参数信息
     */
    protected function go($message, $type = 0, $url = '', $auto = true)
    {
        $this->assign([
            'title'   => $type == 0 ? '操作出现错误' : '操作成功',   // 提示标题
            'content' => $message,                               // 提示信息
            'url'     => $url,                                   // 跳转页面
            'type'    => $type,                                  // 跳转类型
            'auto'    => $auto,                                  // 是否自动跳转
        ]);

        $this->display('Layout/error');
    }

    /**
     * render() 视图渲染
     * @access protected
     * @params string      $file   视图文件
     * @param  mixed|array $params 注入的变量
     * @return true 返回true
     */
    protected function render($file = null, $params = [])
    {
        if ($params) $this->assign($params);
        $this->display($file);
        return true;
    }
}
<?php
namespace Admin\Controller;

use \Common\Auth;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends \Common\Controller
{

    // 初始化验证用户登录
    public function _initialize()
    {

    }

    // 显示登录页面
    public function index()
    {
        layout(false);
        // 判断是否已经登录 跳转到首页
        if ($this->isLogin()) {
            $this->assign([
                'menus' => Auth::getUserMenus(session($this->_admin.'.id')),
                'users' => session($this->_admin)
            ]);
            $strTemplate = 'Index/index';
        } else {
            $strTemplate = 'Layout/login';
        }

        // 显示页面
        $this->display($strTemplate);
    }

    // 开始登录
    public function login()
    {

        // 如果已经登录
        if ($this->isLogin()) {
            if (!IS_AJAX) $this->redirect('Index/index');
            $this->arrError['status'] = 1;
            $this->arrError['msg']    = '已经登录,正在为您跳转...';
        } else {
            // 判断是否有数据提交
            if (isset($_POST) && ! empty($_POST)) {
                // 创建模型对象
                $model  = M('admin');
                $isTrue = $model->validate([
                    ['username', 'require', '登录名不能为空', 1],
                    ['username', '/\S{2,12}/', '登录名需要为2到12个字符', 1],
                    ['password', 'require', '登录密码不能为空', 1],
                    ['password', '/\S{2,12}/', '登录密码需要为6到16个字符', 1],
                ])->create();
                $this->arrError['msg'] = $model->getError();
                if ($isTrue) {
                    // 查询数据是否存在
                    $admin    = $model->where([
                        'username' => $model->username,
                        'password' => sha1($model->password)
                    ])->find();
                    $this->arrError['msg'] = '登录账号或者密码错误!';
                    if ($admin) {
                        // 设置session
                        session('my_admin', $admin);
                        $this->arrError['status'] = 1;
                        $this->arrError['msg']    = '登录成功,正在为您跳转...';
                    }
                }
            }
        }

        $this->ajaxReturn();
    }

    // 退出登录
    public function logout()
    {
        // 查询用户数据
        if ($this->isLogin())
        {
            M('admin')->where(['id' => (int)$_SESSION['my_admin']['id']])->save([
                'last_time' => time(),
                'last_ip'   => get_client_ip(),
            ]);
        }

        // 清楚数据
        unset($_SESSION['my_admin']);
        $this->redirect('Index/index'); // 跳转到登录页
    }
}
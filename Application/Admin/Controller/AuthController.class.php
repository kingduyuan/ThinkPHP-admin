<?php
/**
 * file: 后台管理员页面
 */

// 引入命名空间
namespace Admin\Controller;

// 引入命名空间
use Common\Auth;

class AuthController extends Controller
{
    // 定义查询数据
    public $model = 'auth_item';

    // 查询处理
    public function where($params)
    {
        return [
            'name'  => 'like',
            'where' => ['type' => ['eq', 2]], // 查询权限
        ];
    }

    // 修改数据
    public function update()
    {
        if (IS_AJAX) {
            // 接收参数
            $sType = post('actionType');                  // 操作类型
            $aType = ['delete', 'insert', 'update'];      // 可执行操作
            $iType = (int)get('type');                    // 角色和权限类型 1 角色 2 权限
            $this->arrError['msg'] = "操作类型错误";

            // 操作类型判断
            if (in_array($sType, $aType, true)) {
                /** 逻辑验证
                 * 1 不是管理要验证
                 * 2 删除需要验证
                 * 3 权限操作需要验证
                 * desc: 管理员不验证, 角色操作不是删除不验证、其他一律验证
                 */
                // 开始验证权限
                $this->arrError['msg'] = '抱歉你没有操作权限';
                if ($this->user->id === 1 ||                                                                                        // 管理员不验证
                    ($iType === Auth::ROLE_TYPE && $aType !== 'delete') ||                                                          // 角色操作除删除外不验证
                    ($iType === Auth::AUTH_TYPE && Auth::can($this->user->id, 'updateAuth')) ||                                     // 权限验证操作
                    ($aType === 'delete' && Auth::can($this->user->id, $iType === Auth::ROLE_TYPE ? 'deleteRole' : 'deleteAuth'))   // 删除权限
                ) {
                    // 处理数据返回
                    $isTrue = Auth::handleItem($sType, $iType === Auth::ROLE_TYPE ? Auth::ROLE_TYPE : Auth::AUTH_TYPE, $this->user->id);
                    $this->arrError['msg'] = '服务器繁忙, 请稍候再试...';
                    if ($isTrue === true || is_numeric($isTrue)) {
                        $this->arrError = [
                            'status' => 1,
                            'msg'    => '操作成功 ^.^',
                            'data'   => $isTrue,
                        ];
                    }
                }

            }
        }

        $this->ajaxReturn();
    }
}
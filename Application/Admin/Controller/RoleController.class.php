<?php
/**
 * file: 后台管理员页面
 */

// 引入命名空间
namespace Admin\Controller;

// 引入命名空间
use Common\Auth;

class RoleController extends Controller
{
    // 定义查询数据
    public $model = 'auth_item';

    // 查询处理
    public function where($params)
    {
        // 默认查询
        $where = ['type' => ['eq', 1], 'name' => [['neq', 'admin']]];

        // 模糊查询
        if (isset($params['name']) && ! empty($params['name'])) $where['name'][] = ['like', '%'.$params['name'].'%'];

        // 判断管理员权限：不是管理员只能看到自己的角色
        if ($this->user->id !== 1)
        {
            $arrRoles = Auth::getUserRoles($this->user->id);
            if ($arrRoles) $where['name'][] = ['in', array_keys($arrRoles)];
        }

        return [
            'where' => $where, // 查询角色
        ];
    }

    // 查看角色信息
    public function view()
    {
        // 查询当前角色权限
        $strName = get('name');
        if ($strName)
        {
            // 角色信息存在
            $arrRole = Auth::getRole($strName);
            if ($arrRole && $arrRole['name'] && ($this->user->id == 1 || Auth::hasRole($this->user->id, $arrRole['name'])))
            {
                // 获取用户所有权限
                return $this->render('view', [
                    'role'      => $arrRole,                                                 // 角色信息
                    'roleItems' => Auth::getItemDesc(Auth::getRoleItems($arrRole['name'])),  // 角色自带权限
                    'allMenus'  => Auth::getRoleMenus($arrRole['name']),                     // 角色导航栏信息
                ]);
            }
        }

        // 跳转提示页面
        $this->go('角色信息不存在');
    }

    // 分配权限信息
    public function allocation()
    {
        // 查询当前角色权限
        $strName = get('name');
        if ($strName)
        {
            // 角色信息存在
            $arrRole = Auth::getRole($strName);
            if ($arrRole && $arrRole['name'] && ($this->user->id == 1 || Auth::hasRole($this->user->id, $arrRole['name'])))
            {
                // 获取用户所有权限
                return $this->render('allocation', [
                    'role'      => $arrRole,                                                 // 角色信息
                    'roleItems' => array_keys(Auth::getRoleItems($arrRole['name'])),         // 角色自带权限
                    'powers'    => Auth::getItemDesc(Auth::getUserItems($this->user->id)),   // 用户权限
                ]);
            }
        }

        // 跳转提示页面
        $this->go('角色信息不存在');
    }

    // 修改角色权限
    public function create()
    {
        $name      = post('name');   // 角色名称
        $desc      = post('desc');   // 角色说明
        $arrPowers = post('powers'); // 拥有权限
        if ($name && $desc)
        {
            // 判断数据存在
            $arrRoles = M('auth_item')->field(['name'])->where(['name' => $name])->find();
            if ($arrRoles && ! empty($arrRoles['name']))
            {
                Auth::updateItem($arrRoles['name'], $desc);           // 修改角色
                if (Auth::updateRoleItems($arrRoles['name'], $arrPowers))
                {
                    $this->redirect('/admin/role/view', ['name' => $arrRoles['name']]);
                }
            }
        }

        // 跳转提示页面
        $this->go('提交信息为空');
    }
}
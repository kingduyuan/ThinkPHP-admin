<?php
/**
 * file: Auth.class.php
 * desc: 权限操作处理类
 * user: liujx
 * date: 2016-06-30
 */

namespace Common;

class Auth
{
    // 类常量
    const ROLE_TYPE = 1;
    const AUTH_TYPE = 2;

    // 类定义表名字
    const ADMIN_TABLE = 'admin';
    const AUTH_TABLE  = 'auth_item';
    const CHILD_TABLE = 'auth_child';

    /**
     * handleItem() 处理数据信息
     * @param  string $sType        操作类型('insert', 'update', 'delete')
     * @param  int    $iType        数据类型 1 role 2 auth
     * @param  int    $uid          用户ID
     * @return bool|mixed|string    操作成功返回true insertId 或者错误字符串
     */
    public static function handleItem($sType, $iType, $uid = 0)
    {
        $model   = M(self::AUTH_TABLE);
        $maxData = $model->validate([
            ['name', 'require', '名称不能为空'],
            ['name', '2,50', '长度必须为2到50个字符', 1, 'length'],
            ['desc', 'require', '说明不能为空'],
            ['desc', '2, 255', '长度必须为2到255个字符', 1, 'length']
        ])->create();
        if (false !== $maxData)
        {
            switch ($sType)
            {
                case 'insert': $isTrue = self::createItem($maxData['name'], $model->desc, $iType, $uid);   break;
                case 'update': $isTrue = self::updateItem($maxData['name'], $model->desc);           break;
                default:       $isTrue = self::deleteItem($maxData['name'], $iType);
            }

        }
        else
            $isTrue = $model->getError();

        return $isTrue;
    }

    /**
     * createItem()新增数据信息
     * @param  string $name   名称
     * @param  string $desc   说明
     * @param  int    $iType  类型 1 role 2 auth
     * @return bool|mixed     成功返回 insert_id 失败返回false 或者错误字符串
     */
    public static function createItem($name, $desc, $iType, $uid = 0)
    {
        // 添加数据
        $time = time();
        return M(self::AUTH_TABLE)->add([
            'name'        => $name,
            'desc'        => $desc,
            'type'        => $iType,
            'create_time' => $time,
            'update_time' => $time,
        ]) ? ($iType === self::AUTH_TYPE ? self::addRoleItem('admin', $name) : self::addUserRole($uid, $name)) : false;
    }

    /**
     * updateItem() 修改数据
     * @param  string $name 名称
     * @param  string $desc 说明
     * @return bool   成功返回 true
     */
    public static function updateItem($name, $desc)
    {
        return M(self::AUTH_TABLE)->where(['name' => $name])->save(['desc' => $desc, 'update_time' => time()]);
    }

    /**
     * deleteItem() 删除信息
     * @param  string $name  名称
     * @param  int    $iType 类型
     * @return mixed|string  成功返回 true 失败返回 false
     */
    public static function deleteItem($name, $iType)
    {
        return ($iType === self::ROLE_TYPE
            &&
            (M(self::CHILD_TABLE)->where(['parent' => $name])->count()) > 0)
            ? '这个角色正在使用,不能删除' : (M('auth_item')->where(['name' => $name])->delete());
    }

    /**
     * addRolePower() 给角色添加权限
     * @param  string $role 角色
     * @param  string $auth 权限
     * @return mixed  成功返回insert_id
     */
    public static function addRoleItem($role, $auth)
    {
        return M(self::CHILD_TABLE)->add(['parent' => $role, 'child' => $auth]);
    }

    /**
     * addUserRole() 给用户添加角色
     * @access status
     * @param  int    $uid
     * @param  string $name 角色名
     * @return mixed|string 成功返回true
     */
    public static function addUserRole($uid, $name)
    {
        $uid    = (int)$uid;
        $isTrue = true;
        if ($uid !== 1)
        {
            // 查询用户信息
            $objModel = M(self::ADMIN_TABLE);
            $arrUser  = $objModel->where(['id' => $uid])->find();
            $isTrue   = false;
            if ($arrUser)
            {
                $arrRole = [$name];
                if ($arrUser['roles']) $arrRole = array_merge($arrRole, explode(',', $arrUser['roles']));
                $isTrue = $objModel->where(['id' => $uid])->save(['roles' => implode(',', $arrRole)]);
            }
        }

        return $isTrue;
    }

    /**
     * updateRoleItems() 修改角色的权限
     * @access static
     * @param  string $name  角色名称
     * @param  array  $items 需要添加的权限
     * @return bool   成功返回true
     */
    public static function updateRoleItems($name, $items)
    {
        if ($name)
        {
            try {
                // 删除之前的权限
                M(self::CHILD_TABLE)->where(['parent' => $name])->delete();
                // 添加权限
                if ($items && is_array($items)) foreach ($items as $value) self::addRoleItem($name, $value);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * can() 判断用户是否存在某个权限
     * @param  int    $intUid 用户ID
     * @param  string $items  权限名称
     * @return bool   存在返回 true
     */
    public static function can($intUid, $items)
    {
        $arrItems = self::getUserItems($intUid);
        return $arrItems && isset($arrItems[$items]);
    }

    /**
     * getRole() 获取角色信息
     * @param  string $name 角色名称
     * @return array  返回权限数组
     */
    public static function getRole($name)
    {
        return M(self::AUTH_TABLE)->field(['name', 'desc'])->where(['name' => $name, 'type' => self::ROLE_TYPE])->find();
    }

    /**
     * hasRole() 判断用户是否存在该角色
     * @param  int    $intUid 用户ID
     * @param  string $name   角色名
     * @return bool   存在返回true
     */
    public static function hasRole($intUid, $name)
    {
        $arrUser = M(self::ADMIN_TABLE)->field('roles')->where(['id' => $intUid])->find();
        return  ($arrUser && ! empty($arrUser['roles'])) ? in_array($name, explode(',', $arrUser['roles']), true) : false;
    }

    /**
     * getUserRoles() 获取用户的角色信息
     * @param  int   $intUid 用户ID
     * @return array 返回角色数组
     */
    public static function getUserRoles($intUid)
    {
        $intUid = (int)$intUid;
        // 判断是否为管理员
        $where = ['type' => ['eq', self::ROLE_TYPE]];
        if ($intUid !== 1) {
            $where = ['name' => ''];
            $arrPowers = M(self::ADMIN_TABLE)->field(['roles'])->where(['id' => $intUid])->find();
            if (false !== $arrPowers && ! empty($arrPowers['roles'])) $where['name'] = ['in', $arrPowers['roles']];
        }

        $arrPowers = M(self::AUTH_TABLE)->field(['name', 'desc'])->where($where)->select(['index' => 'name']);
        return Helper::map($arrPowers, 'name', 'desc');
    }

    /**
     * getRoleItems() 获取角色对应的权限信息
     * @param  string $name 角色名称
     * @return array  返回权限数组
     */
    public static function getRoleItems($name)
    {
        $arrPowers = M(self::CHILD_TABLE)->where(['parent' => $name])->select();
        return Helper::map($arrPowers, 'child', 'parent');
    }

    /**
     * getUserItems() 获取用户权限信息
     * @param  int   $intUid 用户权限
     * @return array 返回权限信息
     */
    public static function getUserItems($intUid)
    {
        $arrRoles  = self::getUserRoles($intUid);
        $arrPowers = [];
        if ($arrRoles) foreach ($arrRoles as $key => $value) $arrPowers = array_merge($arrPowers, self::getRoleItems($key));
        return $arrPowers;
    }

    /**
     * getItemDesc() 获取权限说明
     * @param  array $arrPowers 权限信息
     * @return array 返回权限名称和说明信息
     */
    public static function getItemDesc($arrPowers)
    {
        $arrDesc = [];
        if ($arrPowers) $arrDesc = M(self::AUTH_TABLE)->field(['name', 'desc'])->where(['name' => ['in', array_keys($arrPowers)]])->select();
        return Helper::map($arrDesc, 'name', 'desc');
    }

    /**
     * getUserMenus() 根据用户权限获取到导航栏信息
     * @param  int   $uid 用户ID
     * @return array 返回导航栏信息
     */
    public static function getUserMenus($uid)
    {
        return self::getItemMenus(self::getUserItems($uid));
    }

    /**
     * getRoleMenus() 获取角色对应的权限信息
     * @access static
     * @param  string $name 角色名称
     * @return array  返回导航栏信息
     */
    public static function getRoleMenus($name)
    {
        return self::getItemMenus(self::getRoleItems($name));
    }

    /**
     * getItemMenus() 获取权限对应的导航栏信息
     * @access static
     * @param  array $items 权限信息
     * @return array 返回导航栏信息
     */
    public static function getItemMenus($items)
    {
        $arrAllMenus =  [];
        if ($items)
        {
            $arrParents = $arrAllMenus;
            $objModel   = M('menu');

            // 查询栏目数据
            $arrMenus   = $objModel->field(['id', 'pid', 'menu_name', 'icons', 'url'])->where(['url' => ['in', array_keys($items)]])->order(['sort' => 'asc'])->select();
            if ($arrMenus)
            {
                foreach ($arrMenus as $value) if ($value['pid'] != 0) $arrParents[] = (int)$value['pid'];
                // 查询父类
                if ($arrParents)
                {
                    $arrParents = $objModel->field(['id', 'pid', 'menu_name', 'icons', 'url'])->where(['id' => ['in', array_unique($arrParents)]])->order(['sort' => 'asc'])->select();
                    if ($arrParents) $arrMenus = array_merge($arrMenus, $arrParents);
                }
            }

            // 处理栏目数据
            if ($arrMenus)
            {
                foreach ($arrMenus as $key => $value)
                {
                    $pid = $value['pid'];
                    $id  = $value['id'];
                    if ($pid == 0) {
                        // 一级栏目
                        if(isset($arrAllMenus[$id])) $value['child'] =  $arrAllMenus[$id]['child'];
                        $arrAllMenus[$id] = $value;
                    } else {
                        // 不存在创建父类数组
                        if (!isset($arrAllMenus[$pid])) $arrAllMenus[$pid] = array();
                        $arrAllMenus[$pid]['child'][] = $value;
                    }
                }
            }
        }

        return $arrAllMenus;
    }
}
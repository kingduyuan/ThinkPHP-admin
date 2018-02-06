<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/6/28
 * Time: 13:11
 */

namespace Common;


class Helper
{
    /**
     * map() 生成键值对数组
     * @param array  $array 需要处理的数组
     * @param string $key   生成数组的键
     * @param string $value 生成数组的值
     * @return array 返回处理好的数组
     */
    public static function map($array, $key, $value)
    {
        $arrNew = array();
        if ( ! empty($array) && is_array($array)) foreach ($array as $arr) $arrNew[$arr[$key]] = $arr[$value];
        return $arrNew;
    }
}
<?php
namespace Common\Tools;

class ArrayHelper
{
    /**
     * 二维数组替换key(支持一维)
     * @method array_key_replace
     * @param  array         $array   要操作的数据
     * @param  array|string         $old_key 旧的key name
     * @param  array|string         $new_key 新key name
     */
    public static function array_key_replace($array, $old_key, $new_key)
    {
        if (empty($array)) {
            return $array;
        }

        $level = is_array(reset($array)) ? 2 : 1; //判断数组维数

        $old_key = !is_array($old_key) ? array($old_key) : $old_key;
        $new_key = !is_array($new_key) ? array($new_key) : $new_key;
        if (count($old_key) != count($new_key)) {
            return false;
        }

        if ($level === 1) {
            //一维数组
            $keys = array_keys($array);
            $keys = array_combine($keys, $keys);
            $replace_keys = array_combine($old_key, $new_key);
            $keys = array_replace($keys, $replace_keys);
            $values = array_values($array);
            $array = array_combine($keys, $values);
        } else {
            //二维数组
            $keys = array_keys(reset($array));

            $keys = array_combine($keys, $keys);
            $replace_keys = array_combine($old_key, $new_key);
            $keys = array_replace($keys, $replace_keys);
            foreach ($array as $_k => $_v) {
                $values = array_values($array[$_k]);
                $_v = array_combine($keys, $values);
                $array[$_k] = $_v;
            }
        }
        return $array;
    }

    /**
     * 无限极序列排序数据
     * 超过10级则返回错误
     * @method array_tree
     * @param  array     $data  要排序的数据
     * @param  integer    $pid   上级id
     * @param  integer    $level 层级
     * @return array            处理后的数据
     */
    public static function array_tree($data, $pid = 0, $level = 0)
    {
        if ($level >= 10) {
            return false;
        }
        static $list = array();
        foreach ($data as $_k => $_v) {
            if ($_v['pid'] == $pid) {
                $_v['_level'] = $level;
                $list[$_k] = $_v;
                unset($data[$_k]);
                self::array_tree($data, $_v['id'], $level + 1);
            }
        }
        return $list;
    }

    /**
     * 根据二维数组的某个key的值过滤数组
     *
     * @method array2_filter
     * @param  array        $array  要过滤的数组
     * @param  string        $key   数组的key
     * @param  string        $value 数组的值
     * @return array                处理后的数组
     */
    public static function array2_filter($array, $key, $value)
    {

        foreach ($array as $_k => $_v) {
            if ($_v[$key] == $value) {
                unset($array[$_k]);
            }
        }

        $array = self::array_number_key($array);
        return $array;
    }

    /**
     * 用数字作为数组的key
     * @method array_number_key
     * @param  [type]           $array [description]
     * @return [type]                  [description]
     */
    public static function array_number_key($array)
    {
        $ArrayKey = range(0, count($array) - 1);
        $array = array_combine($ArrayKey, $array);

        return $array;
    }
}

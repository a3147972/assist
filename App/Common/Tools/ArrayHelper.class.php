<?php
namespace Common\Tools;

class ArrayHelper
{
    /**
     * 二维数组替换key
     * @param array &$array  要操作的数组
     * @param string $key     原key名
     * @param string $new_key 新key名
     */
    public static function ArrayKeyReplace(&$array, $key, $new_key)
    {
        array_walk($array, function (&$value) use ($key, $new_key) {
            $value[$new_key] = $value[$key];
            unset($value[$key]);
        });
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

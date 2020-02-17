<?php


namespace App\Traits;


/**
 * Trait ArraySort
 * @package App\Traits
 */
trait ArraySort
{
    /**
     * 二维数组根据指定键的键值排序
     * @param $array
     * @param $keys
     * @param string $sort
     * @return array
     */
    public function ArraySort($array, $keys, $sort='asc'){
        $newArr = $valArr = array();
        foreach ($array as $key=>$value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ?  asort($valArr) : arsort($valArr);
        reset($valArr);
        foreach($valArr as $key=>$value) {
            $newArr[$key] = $array[$key];
        }
        return $newArr;
    }
}
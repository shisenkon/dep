<?php
/**
 * Created by PhpStorm.
 * User: Константин
 * Date: 24.06.2017
 * Time: 4:59
 */

$str = "app.ext.list.a101 = from-context";
list($keys, $value) = explode(" = ", $str);
$keys = array_reverse(explode('.', $keys));
foreach($keys as $key)
{
   $temp = [];
   $temp[$key] = $value;
   $value = $temp;
}
print_r($temp);
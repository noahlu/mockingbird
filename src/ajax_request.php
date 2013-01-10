<?php 

/*
 * 数组遍历值格式化 
 * @param {Array} $arr
 * @param {Function} $func 值处理函数
 */
function arrayRecursive ($arr, $func) {
    foreach($arr as $key => $val) {
        if (is_array($val)) {
            arrayRecursive($val, $func);   
        } else {
            $arr[$key] = $func($val);    
        }    
    }
    return $arr;
}

/*
 * AJAX返回值封装为JSON格式 
 * @param {Array} $arr 包涵返回值的数组 
 * @param {String} $stat ajax状态 'fail'/'ok' 
 */
function rspEncode($arr, $stat) {


    if ($stat == "ok") {
        $rspArr = array_merge($arr, array("stat"=>"ok"));    
    } else {
        $rspArr = array_merge($arr, array("stat"=>"fail"));    
    }

    // 中文字符处理
    $rspArr = arrayRecursive($rspArr, "urlencode");

    return urldecode(json_encode($rspArr));
}

if (isset($_GET['action'])) {
    //echo rspEncode(array("action"=>$_GET['action']), 'ok');    
    
    // 检查日志html文件名是否重复
    if ($_GET['action'] == "file_name_check") {

        include_once 'check.php';

        if (isset($_GET['fileName']) && isset($_GET['fileCategory'])) {
            include_once 'conn.php';
            $fileName = $_GET['fileName'];
            $fileCategory = $_GET['fileCategory'];

            if ($fileName == "index") {
                echo rspEncode(array("error"=>"NO_INDEX", "msg"=>"文件名不能为index"), 'ok');    
            } elseif($test = mysql_fetch_array(mysql_query("SELECT * FROM $artTable WHERE post_category = '$fileCategory' AND post_fileName = '$fileName' ", $mysqlDB))) {
                echo rspEncode(array("error"=>"FILENAME_EXIST","msg"=>"文件名重复啦"), 'ok');
            } else {
                echo rspEncode(array("msg"=>"文件名可用"), 'ok');
            }
        } else {
            echo rspEncode(array("msg"=>"文件名或目录参数不正确"), 'fail');    
        }  
    }


} else {
    echo rspEncode(array("msg"=>"参数不正确"), 'fail');    
}


?>

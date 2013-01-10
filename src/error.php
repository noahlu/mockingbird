<?php 
include_once "conn.php";

// siteinfo
$sqlResult = mysql_query("SELECT * FROM $optionTable WHERE autoload = 'yes'");
while ( $result = mysql_fetch_array($sqlResult)) {

    switch($result['option_name']){
        case 'siteurl':
            $siteUrl = $result['option_value']; 
            break;
        case 'blogname':
            $siteName = $result['option_value']; 
            break;
        case 'blogdescription':
            $siteDesc = $result['option_value']; 
            break;
        // no default;    
    }
}
$metaTitle = "操作失败";
include_once 'templates/post_head.php';
?>
<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>操作失败</strong> <a href="/">返回首页</a>。
</div>

<?php
include_once 'templates/post_foot.php';
?>

<?php
$title = "设置";
$adminMenu = "settings";
include_once "templates/admin_head.php";
include_once "conn.php";

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
        case 'blogMetaTitle':
            $blogMetaTitle = $result['option_value']; 
            break;
        // no default;    
    }
}

// sign表示是否是表单提交
if (isset($_POST['sign']) && $_POST['sign'] == "true"){
    
    $sign = true;

    // 标识参数是否正确
    $pass = true;
    
    // 以下是表单校验
    // TODO: 完善校验规则
    if (!empty($_POST['siteUrl'])) {
        $siteUrl = $_POST['siteUrl'];
    } else {
        echo "Site url required! <br/>";
        $pass = false;
    }

    if (!empty($_POST['siteName'])) {
        $siteName = $_POST['siteName'];
    } else {
        echo "siteName required! <br/>";    
        $pass = false;
    }

    if (!empty($_POST['siteDesc'])) {
        $siteDesc = $_POST['siteDesc'];
    } else {
        echo "site Descript required! <br/>";    
        $pass = false;
    }

    $blogMetaTitle = empty($_POST['blogMetaTitle']) ? "" : $_POST['blogMetaTitle'];

    function updateOption ($optionArray) {
        global $optionTable;
        global $mysqlDB;

        while (list($key, $value) = each($optionArray)) {
            //echo "key : $key; value : $value";
            mysql_query("UPDATE $optionTable SET option_value = '$value' WHERE option_name = '$key'");
        };
    }

    if ($pass) {
        updateOption(array('siteurl' => "$siteUrl", 'blogname' => "$siteName", 'blogdescription' => "$siteDesc", 'blogMetaTitle' => "$blogMetaTitle"));
    }
}    

include_once 'templates/admin_leftNav.php';
?>

<div class="span10 content">
    <div class="page-header ">
        <h2>站点设置</h2>
    </div>
    <form class="form-horizontal" action="settings.php" method="post">
        <input name="sign" value="true" type="hidden"/>
        <div class="control-group">
            <label class="control-label" for="siteUrl">网站URL</label>
            <div class="controls">
                <input type="text" name="siteUrl" class="span3" <?php if(isset($siteUrl)){echo "value=\"$siteUrl\"";} ?> id="siteUrl" placeholder="Site Url">
                <span class="help-inline "><em></em></span>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="siteName">网站名称</label>
            <div class="controls">
                <input type="text" name="siteName" id="siteName" class="span3" placeholder="Site Name" <?php if(isset($siteName)){echo "value=\"$siteName\"";} ?>>
                <span class="help-inline "><em></em></span>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="siteDesc">网站描述</label>
            <div class="controls">
                <input type="text" name="siteDesc" id="siteDesc" class="span5" placeholder="A few words describe your site." <?php if(isset($siteDesc)){echo "value=\"$siteDesc\"";} ?> >
                <span class="help-inline "><em></em></span>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="blogMetaTitle">网站首页Title</label>
            <div class="controls">
                <input type="text" name="blogMetaTitle" id="blogMetaTitle" class="span5" placeholder="Site Homepage Meta Title " <?php if(isset($blogMetaTitle)){echo "value=\"$blogMetaTitle\"";} ?> >
                <span class="help-inline "><em></em></span>
                <span class="help-block"></span>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-info">保存</button>
            </div>
        </div>
    </form>

</div>

<?php
include_once "templates/admin_foot.php";
?>


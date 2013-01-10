<?php
include_once 'check.php';
include_once 'conn.php';

$title = isset($title) ? $title : "";
$postTitle = isset($postTitle) ? $postTitle : "";

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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title><?php echo "$title $postTitle";?></title>
<link rel="stylesheet" href="/assets/lib/bootstrap/css/bootstrap.css" />
<style type="text/css">
.navbar .brand{color:#fff;}
.main{width:990px;margin:0 auto;}
.content{margin-bottom:50px;}
.nav-left{margin-top:50px;}
.footer{clear:both;width:940px;margin:0 auto;}
.footer-seperater{background-color:#FFEF32;height:10px;}
.footer-info{margin:0 auto; width:990px;padding:20px 0;}
.tabs-left.affix{top:30px;}

.mg-l-20{margin-left:20px;}
.mg-l-no{margin-left:0;}

.nav-tabs > .active > a > [class^="icon-"],
.nav-tabs > .active > a > [class*=" icon-"]{
  background-image: url("/assets/lib/bootstrap/img/glyphicons-halflings.png");
}
</style>

<script charset="utf-8" src="/assets/lib/seajs/sea.js"></script>
<script type="text/javascript">
seajs.config({
    alias : {
        'jquery': '/assets/lib/jquery/1.8.1/jquery.min.js',       
        'bootstrap': '/assets/lib/bootstrap/js/bootstrap.min.js',       
    },
    preload: 'jquery',
    //debug: true,
    charset: 'utf-8'
});
seajs.modify('jquery', function(require, exports) {
  window.jQuery = window.$ = exports
});
seajs.modify('bootstrap', function(require, exports, module) {
  module.exports = $.bootstrap;
});
</script>

</head>
<body class="main">
        <div class="navbar navbar-inverse navbar-static-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="/"><?php echo $siteName;?></a>
                    <div class="pull-left navbar-text" ><?php echo $siteDesc;?></div>
                    <?php if(isset($_SESSION['userName'])) {?>
                    <div class="pull-right navbar-text">Hi, <?php echo $_SESSION['userName'];?> [ <a href="/admin/userLogout.php">Logout</a> ]</div>
                    <?php }?>
                </div>
            <!--/.contsiner -->
            </div>
        </div>
        <!--/.navbar -->

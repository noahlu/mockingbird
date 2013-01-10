<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title><?php echo "$metaTitle - $siteName";?></title>
<link rel="stylesheet" charset="utf-8" href="/assets/lib/google-code-prettify/prettify.css" />
<link rel="stylesheet" charset="utf-8" href="/assets/lib/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" charset="utf-8" href="/assets/css/global.css" />
<script charset="utf-8" src="/assets/lib/seajs/sea.js"></script>
<script type="text/javascript">
seajs.config({
    alias : {
        'jquery': '/assets/lib/jquery/1.8.1/jquery.min.js',       
        'bootstrap': '/assets/lib/bootstrap/js/bootstrap.min.js',       
        'prettify': '/assets/lib/google-code-prettify/prettify.js',       
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
seajs.modify('prettify', function(require, exports, module) {
  module.exports = $.prettify;
});
</script>
</head>
<body class="main">
<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="/"><?php echo $siteName;?></a>
            <div class="pull-left navbar-text" ><?php echo $siteDesc;?></div>
            <?php if( isset($_SESSION['userName']) ) {?>
            <div class="pull-right navbar-text">Hi, <?php echo $_SESSION['userName'];?> [ <a href="/admin/userLogout.php">Logout</a> ]</div>
            <?php }?>
            <div class="nav-collapse collapse pull-right">
                <ul class="nav">
                    <li><a href="<?php echo $siteUrl;?>/about.htm">About</a></li>
                    <li><a href="<?php echo $siteUrl;?>/portfolio.htm">Portfolio</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    <!--/.contsiner -->
    </div>
</div>
<!--/.navbar -->

<div class="main-content clearfix">


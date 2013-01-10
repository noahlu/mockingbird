<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title><?php echo "$title";?></title>
<link rel="stylesheet" href="/assets/lib/bootstrap/css/bootstrap.css" />
<style type="text/css">
.main{width:990px;margin:0 auto;}
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
    <div class="header"></div>

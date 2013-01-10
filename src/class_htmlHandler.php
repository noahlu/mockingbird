<?php 
/**
 * HTML文件和目录的创建和删除以及路径获取
 * var 为配置项，暂时人肉维护
 * TODO:后台来配置这些var的数据
 * @param {string} $htdocsDir html文件根目录
 * @param {string} $adminDir admin根目录
 * @param {string} $preview 日志预览php文件名（带后缀）
 * @param {string} $artPrefix 默认日志文件名前缀 如'article'
 * @param {string} $fileExt 生成的html扩展名 如'.htm'
 */
class HtmlHandler {
    
    var $htdocsDir = "htdocs";
    var $adminDir = "admin";
    var $preview = "post.php";
    var $page = "page.php";
    var $artPrefix = "article";
    var $fileExt = ".htm";

    /**
     * 拼接日志文件路径和文件名
     * @param {string} $fileName 文件名
     * @param {string} $postId 日志编号
     * @param {string} $postCate 日志路径 如'/cate1/cate2'
     * @return {string} i.e. "/cate1/article1.htm"
     */
    function getHTMLLoc ($postCate, $fileName, $postId) {
        return ($postCate == "/" ? "$postCate" :"$postCate/"). ($fileName ? $fileName : ($this->artPrefix. $postId)). $this->fileExt;
    }

    /**
     * 获取生成html的php文件URL 
     * @param {string} $postId 日志编号
     * @param {string} $postCate 日志分类 非必须
     * @param {boolean} $isCate 是否为目录index页面 非必须
     * @param {boolean} $isHome 是否为博客index页面 非必须
     * @return {string} i.e. "http://www.example.com/post.php?id=1"
     */
    function getPreviewUrl ($postId, $postCate, $isCate = false, $isHome = false) {
        $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
        $rootUrl = "$protocol://".$_SERVER["SERVER_NAME"];

        if ($isCate) {
            if ($isHome) {
                return "$rootUrl/$this->adminDir/$this->page?home=true";
            } else {
                return "$rootUrl/$this->adminDir/$this->page?postTaxonomy=".strtok($postCate, "/");
            }
        }else {
            return "$rootUrl/$this->adminDir/$this->preview?id=$postId";
        }
    }

    /**
     * 删除html文件和空目录 
     * @return {string} log 执行日志 
     */
    function removeHTML ($postCate, $fileName, $postId) {
        $htmlDir = $this->htdocsDir. $postCate;
        $htmlLoc = $this->htdocsDir. $this->getHtmlLoc($postCate, $fileName, $postId);
        $log = "";

        if (unlink($htmlLoc)) {
            $log .= "$htmlLoc removed...<br/>";
        } else {
            $log .= "remove file error!<br/>";    
        }

        // TODO: 缺少多级目录的判断
        if ($postCate != "" && rmdir($htmlDir)) {
            $log .= "$htmlDir removed.";   
        } else {
            $log .= "remove dir error!";    
        }

        return $log;
    }

    /**
     * 生成html文件和目录 
     * @return {string} log 执行日志 
     */
    function generateHTML ($postCate, $fileName, $postId) {

        // i.e. "htdocs/cate1"
        $htmlDir = $this->htdocsDir. $postCate;

        $log = "";

        // create dir
        if (is_dir($htmlDir)) {
            $log .= "DIR: $htmlDir is already exists!<br/>";
        } else {
            mkdir($htmlDir, 0777, true);   
            $log .= "Finish creating dir...<br/>";
        }

        // Create Html File
        // i.e. $htmlLoc: "htdocs/cate1/article1.htm"
        $htmlLoc = $this->htdocsDir. $this->getHtmlLoc($postCate, $fileName, $postId);
        if ($fileName == "index") {
            if ($postCate == "/") {
                $htmlStr = file_get_contents($this->getPreviewUrl($postId, $postCate , true, true));
            } else {
                $htmlStr = file_get_contents($this->getPreviewUrl($postId, $postCate , true, false));
            }
        } else {
            $htmlStr = file_get_contents($this->getPreviewUrl($postId, "" ,false ,false));
        }

        file_put_contents($htmlLoc, $htmlStr);
        $log .= "Finish creating html file: $htmlLoc <br/>";

        return $log;
    }
}

?>

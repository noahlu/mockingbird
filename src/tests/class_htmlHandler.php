<?php 
echo "start testing............<br/>";

/* getHTMLLoc */
$postHTMLLocHaveName = "/cate/name.htm";
$postHTMLLocNoName = "/cate/article1.htm";
$postHTMLLocIndex = "/index.htm";

include_once '../class_htmlHandler.php';
$htmlHandler = new HtmlHandler();

if ($postHTMLLocHaveName === $htmlHandler->getHTMLLoc("/cate","name",1)) {
    echo "getHtmlLoc(has file name) method  passed!<br/>";
} else {
    echo $htmlHandler->getHTMLLoc("cate","name",1)."<br/>";
    echo "getHtmlLoc(has file name) method  failed!<br/>";
}

if ($postHTMLLocNoName === $htmlHandler->getHTMLLoc("/cate","",1)) {
    echo "getHtmlLoc(no file name) method  passed!<br/>";
} else {
    echo "getHtmlLoc(no file name) method  failed!<br/>";
}

if ($postHTMLLocIndex === $htmlHandler->getHTMLLoc("/","index",1)) {
    echo "getHtmlLoc(index) method  passed!<br/>";
} else {
    echo "getHtmlLoc(index) method  failed!<br/>";
}

/* getPreviewUrl */

$previewHomeIndex = "http://blog.noahlu.com/admin/page.php?home=true";
$previewCateIndex = "http://blog.noahlu.com/admin/page.php?postTaxonomy=cateTest";
$previewPost = "http://blog.noahlu.com/admin/post.php?id=1";

if ($previewHomeIndex === $htmlHandler->getPreviewUrl(1,"cateTest",true,true)) {
    echo "getPreviewUrl home index passed!.<br/>";
} else {
    //echo $previewHomeIndex."<br/>";
    //echo $htmlHandler->getPreviewUrl(1,"cateTest",true,true)."<br/>";
    echo "getPreviewUrl home index failed!.<br/>";
}

if ($previewCateIndex === $htmlHandler->getPreviewUrl(1,"cateTest",true,false)) {
    echo "getPreviewUrl category index passed!.<br/>";
} else {
    //echo $previewCateIndex."<br/>";
    //echo $htmlHandler->getPreviewUrl(1,"cateTest",true,false)."<br/>";
    echo "getPreviewUrl category index failed!.<br/>";
}

if ($previewPost === $htmlHandler->getPreviewUrl(1,"index",false,false)) {
    echo "getPreviewUrl post passed!.<br/>";
} else {
    //echo $previewPost."<br/>";
    //echo $htmlHandler->getPreviewUrl(1,"cateTest",false,false)."<br/>";
    echo "getPreviewUrl post failed!.<br/>";
}

?>

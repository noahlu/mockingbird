<?php

if (empty($_GET['postTaxonomy']) && empty($_GET['home'])) {
    echo "wrong category!";
    exit;
}

$postCate = "/". $_GET['postTaxonomy'];

include_once "conn.php";
include_once "class_htmlHandler.php";
$htmlHandler = new HtmlHandler();

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

// taxonomy
$taxoArray = array();
$taxoCateArray = array();
$taxoTagArray = array();
$taxoResult = mysql_query("SELECT * FROM $taxonomyTable WHERE id != ''", $mysqlDB);
while ($taxoRow = mysql_fetch_array($taxoResult)) {
    $taxoArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    if ($taxoRow['type'] == "category") {
        $taxoCateArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    } elseif ($taxoRow['type'] == "tag") {
        $taxoTagArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    }
}

function getTaxonomyDesc ($taxonomy) {
    global $taxoArray;
    return empty($taxoArray[$taxonomy]['description']) ? strtok($taxonomy, "/") : $taxoArray[$taxonomy]['description'];
}    

// 随机文章
$randomArtArray = array();
$randomArtResult = mysql_query("SELECT * FROM $artTable WHERE post_category != '".$postCate."' order by post_date limit 0,10", $mysqlDB);
while ($randomRow = mysql_fetch_array($randomArtResult)) {
    if (!array_key_exists($randomRow['post_category'], $taxoCateArray)) {
        continue;    
    }
    $randomHtmlLoc = $htmlHandler->getHTMLLoc($randomRow['post_category'], $randomRow['post_fileName'], $randomRow['id']);
    $randomArtArray = array_merge($randomArtArray, array($randomRow['post_title']=>$randomHtmlLoc));    
}

if (isset($_GET['home']) && $_GET['home'] == "true") {
    $postResult = mysql_query("SELECT * FROM  $artTable WHERE post_excerpt != '' order by post_date desc limit 0,20", $mysqlDB);
    $cateDesc = "";
    $metaTitle = !empty($blogMetaTitle) ? $blogMetaTitle : "Mocking Bird Blog System";
} else{
    $postResult = mysql_query("SELECT * FROM  $artTable WHERE post_category = '$postCate' order by post_date desc", $mysqlDB);
    // TODO: 可扩展，让用户在后台编辑分类首页Title
    $metaTitle = $cateDesc = getTaxonomyDesc($postCate);
}

include_once 'templates/post_head.php';
?>

<div class="content-post span9">
    <?php if (!empty($cateDesc)) {?>
    <div class="page-header">
        <h1><?php echo $cateDesc;?></h1>
    </div>
    <?php } ?>

    <?php 
    while ($dataRow = mysql_fetch_array($postResult) ) {
        if( empty($dataRow['post_excerpt']) ) {
            continue;    
        }
        $htmlLoc = $htmlHandler->getHtmlLoc($dataRow['post_category'], $dataRow['post_fileName'], $dataRow['id']); 
    ?>
    <div class="page-post-info" >
        <h2><a href="<?php echo $htmlLoc;?>"><?php echo $dataRow['post_title'];?></a></h2>
        <p class="muted"><em><?php echo $dataRow['post_date'];?></em></p>
        <div class="page-post-excerpt"><?php echo (empty($dataRow['post_excerpt']) ? "<p>详情被藏起来了，点击标题查看哦～。</p>" : $dataRow['post_excerpt']); ?></div>
        <a href="<?php echo $htmlLoc;?>" class="btn btn-warning">查看全文 >></a>
    </div>

    <?php
    }
    ?>    

    <a href="/sitemap.htm#<?php echo strtok($postCate, '/');?>" class="page-more-post">更多内容</a>

</div>

<?php
include_once 'templates/post_side.php';
?>

<?php
include_once 'templates/post_foot.php';
?>

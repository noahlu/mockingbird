<?php
include_once 'check.php';
include_once "conn.php";

include_once "class_htmlHandler.php";
$htmlHandler = new htmlHandler();

if (!empty($_GET['id'])) { // post
    $postId = $_GET['id'];
    $postQuery = "SELECT * FROM  $artTable WHERE id = $postId";
    $dataRow = mysql_fetch_array(mysql_query($postQuery, $mysqlDB));
    // generate current post
    $htmlHandler->generateHTML($dataRow['post_category'], $dataRow['post_fileName'], $postId);
    echo "Generated Post :". $dataRow['post_fileName']. ".htm !</br>";

} elseif (!empty($_GET['home']) && $_GET['home'] == "true") { // site index
    
    // generate site index
    $htmlHandler->generateHTML("/", "index", "");
    echo "Generated site index.htm !</br>";
} elseif (!empty($_GET['postTaxonomy'])){ // category index
    $postCate = $_GET['postTaxonomy'];
    
    // generate category index
    $htmlHandler->generateHTML($postCate, 'index', "");
    echo "Generated category". $postCate. "index.htm!</br>";
    
    // generate post under current category
    $cateResult = mysql_query("SELECT * FROM $artTable WHERE post_category = '$postCate'", $mysqlDB);
    while ($dataRow = mysql_fetch_array($cateResult)){
        $htmlHandler->generateHTML($postCate, $dataRow['post_fileName'], $dataRow['id']);
        echo "Generated Post :". $dataRow['post_fileName']. ".htm !</br>";
    }
} elseif (!empty($_GET['generateAll']) && $_GET['generateAll'] == "true"){ // whole site
    
    $timerStart = microtime();

    // generate site index
    $htmlHandler->generateHTML("/", 'index', "");
    echo "Generated site index!<br/>";

    // generate category index
    $cateResult = mysql_query("SELECT * FROM $taxonomyTable", $mysqlDB);
    while ($cateRows = mysql_fetch_array($cateResult)) {
        $htmlHandler->generateHTML($cateRows['taxonomy'], 'index', "");
        echo "Generated category". $cateRows['taxonomy']. " index.htm!<br/>";
    }

    // generate post
    $postResult = mysql_query("SELECT * FROM $artTable", $mysqlDB);
    while ($postRows = mysql_fetch_array($postResult)){
        $htmlHandler->generateHTML($postRows['post_category'], $postRows['post_fileName'], $postRows['id']);    
        echo "Generated post ". $postRows['post_fileName'] .".htm !<br/>";
    }

    $timerEnd = microtime();
    echo "Time Elapsed: ". ($timerEnd - $timerStart)."ms.<br/>";
    
} else {
    exit("Wrong Parameter!");
}
?>

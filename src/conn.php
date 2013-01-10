<?php
include_once "dbConfig.php";

$mysqlDB = mysql_connect($dbhost, $dbuser, $dbpass);

if (!$mysqlDB) {
    echo "Could not connect: ". mysql_error(). "<br/>";
}

mysql_select_db($dbName_blog, $mysqlDB);

?>

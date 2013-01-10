<?php
include_once 'check.php';

// create blog database
if (mysql_query("CREATE DATABASE ". $dbName_blog, $mysqlDB)) {
    echo "Database  $dbName_blog created <br/>";
} else {
    echo "Error creating article database: ". mysql_error(). "<br/>";
}

$artTableStr = "CREATE TABLE ". $artTable. "(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
post_title text,
post_metaTitle text,
post_content longtext,
post_excerpt text,
post_status varchar(20) DEFAULT 'publish',
comment_status varchar(20) DEFAULT 'open',
post_category text,
post_fileName text,
post_date datetime
)";

include_once "conn.php";

// create article table
if (!mysql_query($artTableStr, $mysqlDB)) {
    echo mysql_error(). "<br/>";     
} else {
    echo "Table: $artTable created! <br/>";
}

// create user table
$userTableStr = "CREATE TABLE ". $userTable. "(
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
user_name VARCHAR(254),
user_pass VARCHAR(254)
user_email VARCHAR(254),
user_group VARCHAR(20) DEFAULT 'editor',
user_status VARCHAR(20) DEFAULT 'inactive',
date_created datetime
)";

if (!mysql_query($userTableStr, $mysqlDB)) {
    echo mysql_error(). "<br/>";     
} else {
    echo "Table: $userTable created! <br/>";
}

// create taxonomy table
$taxonomyTableStr = "CREATE TABLE $taxonomyTable (
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
taxonomy VARCHAR(32),
type VARCHAR(20),
description longtext,
parent bigint(20),
count bigint(20)
)";

if (!mysql_query($taxonomyTableStr, $mysqlDB)) {
    exit (mysql_error());    
} else {
    echo "Table: $taxonomyTable created! <br/>";
}


// create options table
$optionTableStr = "CREATE TABLE $optionTable (
id int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id),
autoload VARCHAR(20) DEFAULT 'yes',
option_name VARCHAR(64),
option_value longtext
)";

if (!mysql_query($optionTableStr, $mysqlDB)) {
    exit (mysql_error());    
} else {
    echo "Table: $optionTable created! <br/>";
}

function insertOption ($optionArray) {

    global $optionTable;
    global $mysqlDB;

    while (list(, $value) = each($optionArray)) {
       //echo "$value <br/>";   
       mysql_query("INSERT INTO $optionTable (id, option_name) VALUES ('', '$value')", $mysqlDB) or die(mysql_error());
    }
}

// init optionTable data
insertOption(array("siteurl", "blogname", "blogdescription", "blogMetaTitle", "adminemail"));
echo "Table: $optionTable data insert! <br/>";

mysql_close($mysqlDB);
?>

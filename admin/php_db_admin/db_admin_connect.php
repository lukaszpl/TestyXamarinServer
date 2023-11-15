<?php 

$localhost = "lukaszpxamarin.mysql.db";
$username = "lukaszpxamarin";
$password = "5Vf23pqTjCMcBAg";
$dbname = "lukaszpxamarin";

// create connection
$connect = new mysqli($localhost, $username, $password, $dbname);

// check connection
if($connect->connect_error) {
	die("connection failed : " . $connect->connect_error);
} else {
	//echo "Successfully Connected";
	$connect->query("SET CHARSET utf8");
    $connect->query("SET NAMES `utf8` COLLATE `utf8_bin`");
}

?>
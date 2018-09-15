<?php
$mysql= new mysqli("34.232.237.131","root","pixectra#simple","remote");
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
?>


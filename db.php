<?php
$mysql= new mysqli("localhost","root","pixectra#simple","remote");
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
?>


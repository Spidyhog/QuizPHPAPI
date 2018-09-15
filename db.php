<?php
$mysql= new mysqli("localhost","root","","wartrezor");
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
?>


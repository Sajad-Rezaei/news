<?php
$serverip="sql102.infinityfree.com";
$dbname="if0_39615501_db";
$username="if0_39615501";
$password="9922026043";
$conn = mysqli_connect($serverip,$username,$password,$dbname);
mysqli_query($conn,"SET NAMES 'utf8'");
mysqli_query($conn,"SET CHARACTER SET 'utf8'");
mysqli_query($conn,"SET character_set_connection = 'utf8'");
?>
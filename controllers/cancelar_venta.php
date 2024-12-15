<?php
session_start();
$_SESSION['lista'] = [];
header("location: ../views/vender.php");
?>
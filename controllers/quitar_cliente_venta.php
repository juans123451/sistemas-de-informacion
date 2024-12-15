<?php
session_start();
$_SESSION['clienteVenta'] = "";
header("location: ../views/vender.php");
?>
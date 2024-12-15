<?php
session_start();
$cliente = $_POST['idCliente'];
$_SESSION['clienteVenta'] = $cliente;
header("location: ../views/vender.php");
?>
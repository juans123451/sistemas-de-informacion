<?php
$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el cliente';
    exit;
}
include_once "../models/funciones.php";

$resultado = eliminarCliente($id);
if(!$resultado){
    echo "Error al eliminar";
    return;
}

header("Location: ../views/clientes.php");
?>
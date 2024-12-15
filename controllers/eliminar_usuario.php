<?php
$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el usuario';
    exit;
}
include_once "../models/funciones.php";

$resultado = eliminarUsuario($id);
if(!$resultado){
    echo "Error al eliminar";
    return;
}

header("Location: ../views/usuarios.php");
?>
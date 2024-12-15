<?php
session_start();

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['mensaje'] = "No se ha seleccionado el producto.";
    $_SESSION['tipo_mensaje'] = "danger"; // Mensaje de error
    header("Location: ../views/productos.php");
    exit;
}

include_once "../models/funciones.php";

$resultado = eliminarProducto($id);

if ($resultado) {
    $_SESSION['mensaje'] = "Producto eliminado con éxito.";
    $_SESSION['tipo_mensaje'] = "success"; // Mensaje exitoso
} else {
    $_SESSION['mensaje'] = "Error al eliminar el producto.";
    $_SESSION['tipo_mensaje'] = "danger"; // Mensaje de error
}

header("Location: ../views/productos.php");
exit;
?>

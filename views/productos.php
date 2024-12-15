<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "../models/funciones.php";
session_start();

if (empty($_SESSION['usuario'])) header("location: login.php");

$nombreProducto = (isset($_POST['nombreProducto'])) ? $_POST['nombreProducto'] : null;
$productos = obtenerProductos($nombreProducto);

$cartas = [
    ["titulo" => "variedad de productos", "icono" => "fa fa-box", "total" => count($productos), "color" => "#3578FE"],
    ["titulo" => "cantidad de productos disponibles ", "icono" => "fa fa-shopping-cart", "total" => obtenerNumeroProductos(), "color" => "#4F7DAF"],
    ["titulo" => "dinero invertido en el inventario", "icono" => "fa fa-money-bill", "total" => "s/.". obtenerTotalInventario(), "color" => "#1FB824"],
    ["titulo" => "Ganancia de las ventas realizadas", "icono" => "fa fa-wallet", "total" => "s/.". calcularGananciaProductos(), "color" => "#D55929"],
];
?>

<div class="container mt-3">
    <h1>
        <a class="btn btn-success btn-lg" href="../controllers/agregar_producto.php">
            <i class="fa fa-plus"></i> Agregar
        </a>
        Productos
    </h1>
    <?php include_once "cartas_totales.php"; ?>

    <!-- Mensaje flotante -->
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_mensaje']; ?> alert-flotante" id="mensaje" role="alert">
            <?= htmlspecialchars($_SESSION['mensaje']); ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <form action="" method="post" class="input-group mb-3 mt-3">
        <input autofocus name="nombreProducto" type="text" class="form-control" placeholder="Escribe el nombre o código del producto que deseas buscar">
        <button type="submit" name="buscarProducto" class="btn btn-primary">
            <i class="fa fa-search"></i> Buscar
        </button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Ganancia</th>
                <th>Existencia</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto->codigo; ?></td>
                    <td><?= $producto->nombre; ?></td>
                    <td><?= 's/.'.$producto->compra; ?></td>
                    <td><?= 's/.'.$producto->venta; ?></td>
                    <td><?= 's/.'. floatval($producto->venta - $producto->compra); ?></td>
                    <td><?= $producto->existencia; ?></td>
                    <td>
                        <a class="btn btn-info" href="../controllers/editar_producto.php?id=<?= $producto->id; ?>">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="../controllers/eliminar_producto.php?id=<?= $producto->id; ?>">
                            <i class="fa fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Script para ocultar el mensaje después de 2 segundos -->
<script>
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.classList.add('fade-out');
            setTimeout(() => mensaje.remove(), 1000);
        }
    }, 2000);
</script>

<style>
    .alert-flotante {
        position: fixed;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        text-align: center;
        width: 80%;
        opacity: 1;
        transition: opacity 1s ease-in-out;
    }

    .fade-out {
        opacity: 0;
    }
</style>

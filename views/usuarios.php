<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "../models/funciones.php";
session_start();
if(empty($_SESSION['idUsuario'])) header("location: login.php");

$usuarios = obtenerUsuarios();
?>
<div class="container">
    <h1>
        <a class="btn btn-success btn-lg" href="../controllers/agregar_usuario.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Administrador
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($usuarios as $usuario){
            ?>
                <tr>
                    <td><?php echo $usuario->usuario; ?></td>
                    <td><?php echo $usuario->nombre; ?></td>
                    <td><?php echo $usuario->telefono; ?></td>
                    <td><?php echo $usuario->direccion; ?></td>
                    <td>
                        <a class="btn btn-info" href="../controllers/editar_usuario.php?id=<?php echo $usuario->id; ?>">
                            <i class="fa fa-edit"></i>
                            Editar
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="../controllers/eliminar_usuario.php?id=<?php echo $usuario->id; ?>">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
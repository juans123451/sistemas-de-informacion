<?php
date_default_timezone_set("America/lima");
include_once "encabezado.php";
include_once "navbar.php";
include_once "../models/funciones.php";

session_start();
if(empty($_SESSION['usuario'])) header("location: login.php");
$cartas = [
    
    ["titulo" => "Ventas de hoy", "icono" => "fa fa-calendar-day", "total" => "s/.".obtenerTotalVentasHoy(), "color" => "#2A8D22"],
    ["titulo" => "Ventas de la semana", "icono" => "fa fa-calendar-week", "total" => "s/.".obtenerTotalVentasSemana(), "color" => "#223D8D"],
    ["titulo" => "Ventas del mes", "icono" => "fa fa-calendar-alt", "total" => "s/.".obtenerTotalVentasMes(), "color" => "#D55929"],
];
$datos = generarDiagramaProductosVendidos();

// Extrae productos y valores
$productos = $datos['productos'];
$valores = $datos['valores'];


$totales = [
	
	["nombre" => "Ventas registradas", "total" => obtenerNumeroVentas(), "imagen" => "../img/ventas.png"],
	["nombre" => "Usuarios registrados", "total" => obtenerNumeroUsuarios(), "imagen" => "../img/usuarios.png"],
	["nombre" => "Clientes registrados", "total" => obtenerNumeroClientes(), "imagen" => "../img/clientes.png"],
];

$ventasUsuarios = obtenerVentasPorUsuario();
$ventasClientes = obtenerVentasPorCliente();
$productosMasVendidos = obtenerProductosMasVendidos();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico en el costado</title>
    <!-- Incluye la librería de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Estilos para posicionar el gráfico */
        .grafico-container {
            position: fixed; /* Fija el contenedor en una posición específica */
            top: 50%; /* Centrado verticalmente */
            right: 100px; /* Alineado al costado derecho, a 10px del borde */
            transform: translateY(-50%); /* Ajuste para centrar verticalmente */
            width: 300px;
            height: 300px;
        }

        canvas {
            display: block; /* Asegura que el canvas ocupe todo el contenedor */
        }
    </style>
</head>
<body>
    <!-- Contenedor del gráfico -->
    <div class="grafico-container">
        <canvas id="pieChart"></canvas>
    </div>

    <script>
        // Configuración del gráfico de pastel
        const ctx = document.getElementById("pieChart").getContext("2d");
        const pieChart = new Chart(ctx, {
            type: "pie",
            data: {
                labels: <?php echo json_encode($productos); ?>, // Nombres de productos desde PHP
                datasets: [{
                    data: <?php echo json_encode($valores); ?>, // Cantidades vendidas desde PHP
                    backgroundColor: [
                        "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false // Permite ajustar el tamaño del canvas
            }
        });
    </script>
</body>
</html>

<div class="container">
	<div class="alert alert-info" role="alert">
		<h1>
			Hola, <?= $_SESSION['usuario']?>
		</h1>
	</div>
	<div class="card-deck row mb-2">
	<?php foreach($totales as $total){?>
		<div class="col-xs-12 col-sm-6 col-md-3" >
			<div class="card text-center">
				<div class="card-body">
					<img class="img-thumbnail" src="<?= $total['imagen']?>" alt="">
					<h4 class="card-title" >
						<?= $total['nombre']?>
					</h4>
					<h2><?= $total['total']?></h2>

				</div>

			</div>
		</div>
		<?php }?>
	</div>

	 <?php include_once "cartas_totales.php"?>
	 <head>
    <style>
        /* Aplica un contenedor con 75% del ancho de la pantalla */
        .card {
            width: 80% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding: 20px !important;
        }

        /* Asegura que la fila contenedora se mantenga centrada en la pantalla */
        .row.mt-2 {
            display: flex;
            justify-content: flex-start; /* Alinea los elementos al inicio de la fila */
        }
    </style>
</head>

	 <div class="row mt-2">
	 	<div class="col">
			<div class="card">
				<div class="card-body">
					<h4>Ventas por usuarios</h4>
					<table class="table">
						<thead>
							<tr>
								<th>Nombre usuario</th>
								<th>Número ventas</th>
								<th>Total ventas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($ventasUsuarios as $usuario) {?>
								<tr>
									<td><?= $usuario->usuario?></td>
									<td><?= $usuario->numeroVentas?></td>
									<td>$<?= $usuario->total?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>	 		
	 	</div>
	 	
	 </div>

	 <h4>10 Productos más vendidos</h4>
	 <table class="table" id="productosMasVendidos">
	 	<thead>
	 		<tr>
	 			<th>Producto</th>
	 			<th>Unidades vendidas</th>
	 			<th>Total</th>
	 		</tr>
	 	</thead>
	 	<tbody>
	 		<?php foreach($productosMasVendidos as $producto) {?>
	 		<tr>
	 			<td><?= $producto->nombre?></td>
	 			<td><?= $producto->unidades?></td>
	 			<td>$<?= $producto->total?></td>
	 		</tr>
	 		<?php }?>
	 	</tbody>
	 </table>
</div>	
<style>
	#productosMasVendidos {
		width: 75% !important; /* Ocupa 3/4 del ancho */
		float: left !important; /* Se alinea al lado izquierdo */
	}
</style>

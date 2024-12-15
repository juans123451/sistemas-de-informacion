<?php
include_once "../models/funciones.php";
require_once "../TCPDF-main/tcpdf.php";

session_start();
$productos = $_SESSION['lista'];
$idUsuario = $_SESSION['idUsuario'];
$total = calcularTotalLista($productos);
$idCliente = $_SESSION['clienteVenta'] ?? null;

if (count($productos) === 0) {
    header("location: ../views/vender.php");
    exit;
}

// Si hay cliente, obtener sus datos. Si no, asignar valores por defecto.
$cliente = $idCliente ? obtenerClientePorId($idCliente) : null;
$nombreCliente = $cliente ? $cliente->nombre : "Cliente Genérico";
$telefonoCliente = $cliente ? $cliente->telefono : "N/A";
$direccionCliente = $cliente ? $cliente->direccion : "N/A";

// Registrar venta
$resultado = registrarVenta($productos, $idUsuario, $idCliente, $total);
if (!$resultado) {
    echo "Error al registrar la venta.";
    exit;
}

// Generar PDF
ob_end_clean(); // Limpiar salida previa
$pdf = new TCPDF();
$pdf->AddPage();

// Configurar título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'BOLETA DE VENTAS', 0, 1, 'C');
$pdf->Cell(0, 10, 'COMPUTACTUS', 0, 1, 'C');

// Información del cliente
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Cliente: {$nombreCliente}", 0, 1);
$pdf->Cell(0, 10, "Teléfono: {$telefonoCliente}", 0, 1);
$pdf->Cell(0, 10, "Dirección: {$direccionCliente}", 0, 1);

$pdf->Ln(5);

// Tabla de productos
$html = '<table border="1" cellpadding="5">
    <tr>
        <th>Código</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
    </tr>';
foreach ($productos as $p) {
    $subtotal = floatval($p->cantidad * $p->venta);
    $html .= "<tr>
        <td>{$p->codigo}</td>
        <td>{$p->nombre}</td>
        <td>\${$p->venta}</td>
        <td>{$p->cantidad}</td>
        <td>\${$subtotal}</td>
    </tr>";
}
$html .= "</table>";

// Escribir tabla en PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, "Total: \${$total}", 0, 1, 'R');

// Limpiar sesión después de generar PDF
$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";

// Salida del PDF
$pdf->Output('boleta_venta.pdf', 'I');
?>

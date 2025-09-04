<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Resumen de Compras</title>
	<style>
		table { border-collapse: collapse; width: 60%; margin-bottom: 20px; }
		th, td { border: 1px solid #888; padding: 8px; text-align: center; }
		th { background: #eee; }
		.resumen { width: 60%; }
		.resumen td { text-align: right; }
		.total { font-weight: bold; color: green; }
		.error { color: red; }
	</style>
</head>
<body>
<h2>Resumen de Compras</h2>
<?php
$compras = [
	["producto" => "Camiseta", "precio" => 8.50, "cantidad" => 2],
	["producto" => "Pantalón", "precio" => 15.00, "cantidad" => 5],
	["producto" => "Zapatos", "precio" => 25.00, "cantidad" => 1],
];

$cupon = $_POST["cupon"] ?? "";
$error = "";
$envio = 2.99;
$subtotal_general = 0;
$descuento_volumen_total = 0;
$descuento_cupon = 0;
$iva = 0;
$envio_aplicado = $envio;

// Calcular subtotales y descuentos por volumen
foreach ($compras as $i => $item) {
	$precio = $item["precio"];
	$cantidad = $item["cantidad"];
	$descuento_vol = 0;
	if ($cantidad >= 5) {
		$descuento_vol = $precio * $cantidad * 0.05;
		$descuento_volumen_total += $descuento_vol;
	}
	$subtotal = ($precio * $cantidad) - $descuento_vol;
	$compras[$i]["subtotal"] = $subtotal;
	$compras[$i]["descuento_vol"] = $descuento_vol;
	$subtotal_general += $subtotal;
}

// Aplicar cupón
if ($cupon !== "") {
	$cupon = strtoupper(trim($cupon));
	if ($cupon === "AHORRO10") {
		$descuento_cupon = $subtotal_general * 0.10;
	} elseif ($cupon === "ENVIOGRATIS") {
		$envio_aplicado = 0;
	} else {
		$error = "Cupón no válido.";
	}
}

$subtotal_con_cupon = $subtotal_general - $descuento_cupon;

// Costo de envío solo si subtotal tras descuentos < $25
if ($subtotal_con_cupon >= 25) {
	$envio_aplicado = 0;
}

$iva = $subtotal_con_cupon * 0.13;
$total = $subtotal_con_cupon + $iva + $envio_aplicado;
?>
<form method="post">
	<label>Ingrese cupón (opcional):
		<input type="text" name="cupon" value="<?php echo htmlspecialchars($cupon); ?>">
	</label>
	<button type="submit">Aplicar</button>
</form>
<?php if ($error): ?>
	<p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<table>
	<tr>
		<th>Producto</th>
		<th>Precio</th>
		<th>Cantidad</th>
		<th>Descuento Volumen</th>
		<th>Subtotal</th>
	</tr>
	<?php foreach ($compras as $item): ?>
	<tr>
		<td><?php echo htmlspecialchars($item["producto"]); ?></td>
		<td>$<?php echo number_format($item["precio"], 2); ?></td>
		<td><?php echo $item["cantidad"]; ?></td>
		<td>$<?php echo $item["descuento_vol"] > 0 ? number_format($item["descuento_vol"], 2) : "-"; ?></td>
		<td>$<?php echo number_format($item["subtotal"], 2); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<table class="resumen">
	<tr><td>Subtotal:</td><td>$<?php echo number_format($subtotal_general, 2); ?></td></tr>
	<?php if ($descuento_cupon > 0): ?>
	<tr><td>Descuento cupón:</td><td>-$<?php echo number_format($descuento_cupon, 2); ?></td></tr>
	<?php endif; ?>
	<tr><td>Subtotal tras descuentos:</td><td>$<?php echo number_format($subtotal_con_cupon, 2); ?></td></tr>
	<tr><td>IVA (13%):</td><td>$<?php echo number_format($iva, 2); ?></td></tr>
	<tr><td>Costo de envío:</td><td>$<?php echo number_format($envio_aplicado, 2); ?></td></tr>
	<tr class="total"><td>Total a pagar:</td><td>$<?php echo number_format($total, 2); ?></td></tr>
</table>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Conversor de Temperaturas</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 40px; }
		.error { color: red; }
		.resultado { color: green; }
	</style>
</head>
<body>
	<h2>Conversor de Temperaturas</h2>
	<?php
	$error = "";
	$resultado = "";
	$valor = "";
	$tipo = "";
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$valor = $_POST["valor"] ?? "";
		$tipo = $_POST["tipo"] ?? "";
		// Validar que sea un número decimal
		if ($valor === "" || !is_numeric($valor) || !preg_match('/^-?\d+(\.\d+)?$/', $valor)) {
			$error = "Por favor, ingrese un número decimal válido.";
		} elseif ($tipo !== "c2f" && $tipo !== "f2c") {
			$error = "Seleccione un tipo de conversión válido.";
		} else {
			$valor = floatval($valor);
			if ($tipo === "c2f") {
				$res = ($valor * 9/5) + 32;
				$resultado = "$valor °C = $res °F";
			} else {
				$res = ($valor - 32) * 5/9;
				$resultado = "$valor °F = $res °C";
			}
		}
	}
	?>
	<form method="post">
		<label>Temperatura:
			<input type="text" name="valor" value="<?php echo htmlspecialchars($valor); ?>" required>
		</label>
		<br><br>
		<label>
			<input type="radio" name="tipo" value="c2f" <?php if($tipo==="c2f") echo "checked"; ?>> Celsius a Fahrenheit
		</label>
		<label>
			<input type="radio" name="tipo" value="f2c" <?php if($tipo==="f2c") echo "checked"; ?>> Fahrenheit a Celsius
		</label>
		<br><br>
		<button type="submit">Convertir</button>
	</form>
	<?php if ($error): ?>
		<p class="error"><?php echo $error; ?></p>
	<?php elseif ($resultado): ?>
		<p class="resultado"><?php echo $resultado; ?></p>
	<?php endif; ?>
</body>
</html>

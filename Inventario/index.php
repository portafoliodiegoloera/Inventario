<?php

$servidor = "localhost:3305";
$usuario = "root"; 
$clave = ""; 
$base_datos = "inventario_crud";

$conexion = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Registrar un nuevo producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar'])) {
    $nombreproducto = $_POST['nombreproducto'];
    $codigo = $_POST['codigo'];

    $stmt = $conexion->prepare("INSERT INTO productos (nombre, codigo) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombreproducto, $codigo);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Eliminar un producto
if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener la lista de productos
$resultado = $conexion->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventarios</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/04a62becee.js" crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center p-3">INVENTARIO DE PRODUCTOS</h1>
    <div class="container-fluid row">
        <form class="col-4" method="post">
            <h3>Registro de Productos</h3>
            <div class="mb-3">
                <label for="nombreproducto" class="form-label">Nombre Del Producto</label>
                <input type="text" class="form-control" id="nombreproducto" name="nombreproducto" required>
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="text" class="form-control" id="cantidad" name="cantidad" required>
            </div>
            <button type="submit" class="btn btn-primary" name="registrar">Registrar</button>
        </form>
        <div class="col-8 p-4">
            <table class="table">
                <thead class="bg-info">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre Del Producto</th>

                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($datos = $resultado->fetch_object()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($datos->codigo) ?></td>
                            <td><?= htmlspecialchars($datos->nombre) ?></td>
                            
                            <td>
                                
                                <a href="?action=eliminar&id=<?= $datos->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<?php
$conexion->close();
?>

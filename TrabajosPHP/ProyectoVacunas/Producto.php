<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'vacuncitas';
$usuario = 'root';
$contraseña = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

function crearProducto($pdo, $nombre_producto, $valor) {
    try {
        // Validar valor
        if ($valor === null || $valor === '' || !is_numeric($valor)) {
            echo "Error: El valor debe ser un número válido.";
            return;
        }

        $valor = floatval($valor); // Convertir a número decimal

        $sql = "INSERT INTO Producto (nombre_producto, valor) 
                VALUES (:nombre_producto, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Producto creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear producto: " . $e->getMessage();
    }
}

function leerProducto($pdo, $nombre_producto) {
    try {
        $sql = "SELECT * FROM Producto WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($productos) {
            foreach ($productos as $producto) {
                echo "Nombre: " . $producto['nombre_producto'] . "<br>";
                echo "Valor: " . $producto['valor'] . "<br><br>";
            }
        } else {
            echo "No se encontraron productos.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar producto: " . $e->getMessage();
    }
}

function actualizarProducto($pdo, $nombre_producto, $valor) {
    try {
        // Validar valor
        if ($valor === null || $valor === '' || !is_numeric($valor)) {
            echo "Error: El valor debe ser un número válido.";
            return;
        }

        $valor = floatval($valor);

        $sql = "UPDATE Producto SET valor = :valor 
                WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Producto actualizado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar producto: " . $e->getMessage();
    }
}

function eliminarProducto($pdo, $nombre_producto) {
    try {
        $sql = "DELETE FROM Producto WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->execute();
        echo "Producto eliminado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion_producto'] ?? '';
    $nombre_producto = htmlspecialchars(trim($_POST['nombre_producto'] ?? ''));
    $valor = $_POST['valor'] ?? null;

    switch ($accion) {
        case 'create':
            crearProducto($pdo, $nombre_producto, $valor);
            break;
        case 'read':
            leerProducto($pdo, $nombre_producto);
            break;
        case 'update':
            actualizarProducto($pdo, $nombre_producto, $valor);
            break;
        case 'delete':
            eliminarProducto($pdo, $nombre_producto);
            break;
        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>

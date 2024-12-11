<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'vacuncitas';
$usuario = 'root';
$contraseña = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}
// Funciones CRUD para "cliente"
function crearCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "INSERT INTO Cliente (nombre, apellido, tipo_documento, numero_documento, telefono, fecha_nacimiento) 
        VALUES (:nombre, :apellido, :tipo_documento, :numero_documento, :telefono, :fecha_nacimiento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->execute();
        echo "Cliente creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear cliente: " . $e->getMessage();
    }
}
function leerCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "SELECT * FROM Cliente WHERE nombre = :nombre OR apellido = :apellido OR tipo_documento = :tipo_documento 
                OR numero_documento = :numero_documento OR telefono = :telefono OR fecha_nacimiento = :fecha_nacimiento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clientes) {
            foreach ($clientes as $cliente) {
                echo "Nombre: " . $cliente['nombre'] . "<br>";
                echo "Apellido: " . $cliente['apellido'] . "<br>";
                echo "Tipo de Documento: " . $cliente['tipo_documento'] . "<br>";
                echo "Número de Documento: " . $cliente['numero_documento'] . "<br>";
                echo "Teléfono: " . $cliente['telefono'] . "<br>";
                echo "Fecha de Nacimiento: " . $cliente['fecha_nacimiento'] . "<br><br>";
            }
        } else {
            echo "No se encontraron cliente con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar cliente: " . $e->getMessage();
    }
}

function actualizarCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "UPDATE Cliente SET tipo_documento = :tipo_documento, numero_documento = :numero_documento, 
                telefono = :telefono, fecha_nacimiento = :fecha_nacimiento, apellido = :apellido 
                WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);

        if ($stmt->execute()) {
            echo "Cliente actualizado exitosamente.";
        } else {
            echo "Error al actualizar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
    }
}

function eliminarCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "DELETE FROM Cliente WHERE nombre = :nombre OR apellido = :apellido OR tipo_documento = :tipo_documento 
                OR numero_documento = :numero_documento OR telefono = :telefono OR fecha_nacimiento = :fecha_nacimiento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);

        if ($stmt->execute()) {
            echo "Cliente eliminado exitosamente.";
        } else {
            echo "Error al eliminar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Depuración: Imprimir los datos recibidos por POST

    $accion = $_POST['accion'] ?? '';
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $tipo_documento = htmlspecialchars(trim($_POST['tipo_documento'] ?? ''));
    $numero_documento = htmlspecialchars(trim($_POST['numero_documento'] ?? ''));
    $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha_nacimiento'] ?? ''));

    // Validación básica para asegurarse de que los campos necesarios no estén vacíos
    if (!$nombre || !$apellido || !$telefono || !$tipo_documento || !$numero_documento || !$fecha_nacimiento) {
        echo "Error: todos los campos son obligatorios.";
        return;
    }

    switch ($accion) {
        case 'create':
            if ($nombre && $apellido && $telefono && $tipo_documento && $numero_documento && $fecha_nacimiento) {
                crearCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento);
            }
            break;
        case 'read':
                leerCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento);
            break;
        case 'update':
            if ($nombre && ($apellido || $telefono || $tipo_documento || $numero_documento || $fecha_nacimiento)) {
                actualizarCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento);
            }
            break;
        case 'delete':
                eliminarCliente($pdo, $nombre, $apellido, $tipo_documento, $numero_documento, $telefono, $fecha_nacimiento);
            break;
        default:
                echo "Error: acción no reconocida.";
            break;
    }
}
?>

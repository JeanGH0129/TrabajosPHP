<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellido = htmlspecialchars(trim($_POST['apellido']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));

    echo "<h1>Gracias por tu mensaje!</h1>";
    echo "<p><strong>Nombre:</strong> " . $nombre . "</p>";
    echo "<p><strong>Apellido:</strong> " . $apellido . "</p>";
    echo "<p><strong>Telefono:</strong> " . nl2br($telefono) . "</p>";
} else {
    echo "No se recibieron datos del formulario.";
}

// Código para inserción en la base de datos:
// Nombre de la Base de datos es: tps2_123 
// Nombre de la tabla: aprendiz

// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'tps2_123';
$usuario = 'root';
$contraseña = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Función para insertar datos en la base de datos
function insertarUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "INSERT INTO aprendiz(nombre, apellido, telefono) VALUES (:nombre, :apellido, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            echo "Registro exitoso.";
        } else {
            echo "Error al registrar los datos.";
        }
    } catch (PDOException $e) {
        echo "Error en la inserción de datos: " . $e->getMessage();
    }
}

// Bloque de recepción de datos del formulario
$nombre = $apellido = $telefono = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono'])) {
        // Asignar y limpiar variables
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $telefono = htmlspecialchars(trim($_POST['telefono']));

        // Llamada a la función de inserción
        insertarUsuario($pdo, $nombre, $apellido, $telefono);
    } else {
        echo "Error: faltan datos obligatorios. Asegúrate de completar todos los campos.";
    }
}
?>


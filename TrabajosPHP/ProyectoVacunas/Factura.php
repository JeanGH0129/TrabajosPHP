<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Menú de navegación -->
    <nav class="bg-blue-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center space-x-2">
                            <a href="index.html">
                                <img src="Logo.img" alt="Logo IPS Vacunate SAS" class="h-12">
                            </a>
                            <a href="index.html" class="text-white font-bold text-lg">IPS Vacunate SAS</a>
                        </div>
                        
                        <div class="flex space-x-4">
                            <a href="Kroom.html" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
                            <a href="Producto.html" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Registrar Producto</a>
                            <a href="Factura.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Registrar Factura</a>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </nav>

    <!-- Formulario de Factura -->
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Registrar Factura</h2>


            <?php
    $nombreCliente = $nombreProducto = $cantidad = $valorTotal = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
        $nombre = htmlspecialchars($_POST["nombre"]);

        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "vacuncitas";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión exitosa a la base de datos <br>";

            // Consulta para obtener el nombre específico y los productos asociados
            $sql = "SELECT cliente.nombre, producto.nombre_producto, producto.valor
                    FROM cliente 
                    JOIN producto ON cliente.id_cliente = producto.id_producto
                    WHERE cliente.nombre = :nombre";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();

            // Almacenar el resultado
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nombreCliente = $row['nombre'];
                $nombreProducto = $row['nombre_producto'];
                
                // Calcular el valor total basado en la cantidad y el valor del producto
                $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
                $valorProducto = $row['valor'];
                $valorTotal = $cantidad * $valorProducto;

                // Insertar los datos en la tabla facturas
                if (isset($_POST['enviar'])) {
                    $sqlInsert = "INSERT INTO facturas (nombre, nombre_producto, cantidad, valor_total) VALUES (:nombre, :nombre_producto, :cantidad, :valor_total)";
                    $stmtInsert = $conn->prepare($sqlInsert);
                    $stmtInsert->bindParam(':nombre', $row['nombre']);
                    $stmtInsert->bindParam(':nombre_producto', $nombreProducto);
                    $stmtInsert->bindParam(':cantidad', $cantidad);
                    $stmtInsert->bindParam(':valor_total', $valorTotal);
                    $stmtInsert->execute();
                    echo "Factura agregada exitosamente.";
                }
            } else {
                echo "No se encontraron resultados para el nombre especificado.";
            }
        } catch(PDOException $e) {
            echo "Error en la conexión o consulta: " . $e->getMessage();
        }
    }
    ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                <!-- Entrada de Cliente -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_cliente">Cliente</label>
                    <input type="text" name="nombre" id="nombre"  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el nombre del cliente"  value="<?php echo $nombreCliente; ?>">
                </div>

                <!-- Entrada de Producto -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_producto">Producto</label>
                    <input type="text" name="nombre_producto" id="nombre_producto" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el nombre del producto"  value="<?php echo $nombreProducto; ?>">
                </div>

                <!-- Cantidad -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese la cantidad" value="<?php echo $cantidad; ?>">
                </div>

                <!-- Valor Total -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="valor_total">Valor Total</label>
                    <input type="number" name="valor_total" id="valor_total" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese el valor total" value="<?php echo $valorTotal; ?>">
                </div>

                <!-- Botones CRUD -->
                <div class="grid grid-cols-2 gap-4">
                <input type="submit" value="buscar">
                <input type="submit" name="enviar" value="agregar">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
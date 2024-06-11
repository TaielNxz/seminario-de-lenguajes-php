<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Pedidos realizados</title>
</head>
<html>
<body>

    <header class="header">
        <a href="index.php">
            <img src="img/logo.png" alt="logo">
        </a>
        <h1>Papa's Pizzeria Argentina</h1>
    </header>

    <main>
        <div class="pedidos">
            <h2>Pedidos Realizados</h2>
        </div>

        <div class="tabla">
            <div class="tabla-contenedor">
                <?php
                    // Conectamos a la base de datos.
                    include "conectarDB.php";

                    // Creamos una consulta `SELECT` para obtener todos los pedidos de la tabla `pedidos`.
                    $pedidos = "SELECT *
                                FROM pedidos
                                ORDER BY id DESC";

                    // Ejecutamos la consulta y almacenamos el resultado en la variable `$result`.
                    $result = mysqli_query($conn, $pedidos);

                    // Recorremos los resultados de la consulta.
                    // En cada iteración del bucle, la variable `$row` contiene la información de un pedido.
                    while ($row = mysqli_fetch_array($result)) {
                        
                        // Obtenemos el ID del item de menú del pedido.
                        $idItemMenu = $row['idItemMenu'];

                        // Obtenemos la información del item de menú.
                        $infoItem = "SELECT id, nombre, precio, tipo, foto
                                 FROM items_menu
                                 WHERE id = $idItemMenu";

                        // Ejecutamos la consulta y almacenamos el resultado en la variable `$result`.
                        $resultItem = mysqli_query($conn, $infoItem);

                        // Obtenemos la información del item de menú.
                        $item = mysqli_fetch_array($resultItem);

                        // Mostramos una tarjeta para cada pedido.
                        ?><div class='card'>
                        <img src='mostrarImagen.php?id=<?php echo $item['id']?>' alt='pizza'>
                        <div class='card-texto'>
                        <p class='nombre'><?php echo $item['nombre']?></p>
                        <p class='tipo'><?php echo $item['tipo']?></p>
                        <p class='precio'><?php echo $item['precio']?></p>
                        </div>
                        </div><?php
                    }

                    // Cerramos la conexión a la base de datos.
                    mysqli_close($conn);
                ?>
            </div>
        </div>
    
    </main>

    <footer class="footer">
        <h3>Integrantes del grupo</h3>
        <p>Juan Ignacio Coelho Soria, Taiel Alen Nunes, 2023</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
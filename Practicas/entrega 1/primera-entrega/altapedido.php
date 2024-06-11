<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Alta Pedido</title>
</head>
<body>


<?php
    session_start();
    
    include "validar.php"
?>
    <header class="header">
        <a href="index.php">
            <img src="img/logo.png" alt="logo">
        </a>
        <h1>Papa's Pizzeria Argentina</h1>
    </header>

    <main>
        <form action="altapedido.php" method="POST" class="formulario-alta">
            <fieldset>
                <div class="campo">
                    <label for="idItemMenu">Item Menú - Comida: </label>
                    <select name="idItemMenu" id="idItemMenu"> 
                         <?php
                            // Importamos el archivo para conectarnos a la base de datos
                            include "conectarDB.php";

                            // Creamos una consulta SQL para seleccionar todos los items del menú del tipo "COMIDA"
                            $comidas = "SELECT id, nombre
                                        FROM items_menu
                                        WHERE tipo = 'COMIDA'";

                            // Creamos una consulta SQL para seleccionar todos los items del menú del tipo "BEBIDA"
                            $bebidas = "SELECT id, nombre
                                        FROM items_menu
                                        WHERE tipo = 'BEBIDA'";

                            // Ejecutamos la consulta SQL para seleccionar todos los items del menú del tipo "COMIDA"
                            $resultComidas = mysqli_query($conn, $comidas);

                            // Ejecutamos la consulta SQL para seleccionar todos los items del menú del tipo "BEBIDA"
                            $resultBebidas = mysqli_query($conn, $bebidas);

                            // Cerramos la conexión con la base de datos
                            mysqli_close($conn); 
                        ?>

                        <optgroup label="comidas">
                            <option value="" disabled selected hidden></option>
                            <?php
                                while ($row = mysqli_fetch_array($resultComidas)) {
                                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                                }
                             ?>
                        </optgroup>
                        <optgroup label="bebidas">
                            <?php
                                while ($row = mysqli_fetch_array($resultBebidas)) {
                                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                                }
                            ?>
                        </optgroup>
                      
                    </select>
                </div>

                <div class="campo">
                    <label for="nromesa">Número de Mesa: </label>
                    <select name="nromesa" id="nromesa">
                            <option value="" disabled selected hidden></option>
                            <option value="1">Mesa 1</option>
                            <option value="2">Mesa 2</option>
                            <option value="3">Mesa 3</option>
                            <option value="4">Mesa 4</option>
                            <option value="5">Mesa 5</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="comentarios">Nota de Pedido: </label>
                    <textarea name="comentarios" id="comentarios"></textarea>
                </div>

                <div class="campo">
                    <input id="procesar" type="submit" value="Procesar Pedido" >
                </div>

                <?php
                if ( isset($_SESSION['pedido_rechazado']) ) {   
                    echo $_SESSION['pedido_rechazado'];
                    unset($_SESSION['pedido_rechazado']);
                }
                ?>

            </fieldset>
        </form>
    </main>

    <footer class="footer">
        <h3>Integrantes del grupo</h3>
        <p>Juan Ignacio Coelho Soria, Taiel Alen Nunes, 2023</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
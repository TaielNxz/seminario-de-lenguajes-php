<!DOCTYPE html>

<?php

    session_start();

    if( !isset($_POST['tipo']) ) {
        $_POST['tipo'] = '';
    } 

    if( !isset($_POST['nombre']) ) {
        $_POST['nombre'] = '';
    } 

    if( !isset($_POST['orden']) ) {
        $_POST['orden'] = '';
    } 

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Papa's Pizzeria Argentina</title>
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

        <form class="formulario" action="index.php" method="POST">
            <fieldset>
                <div class="campo">
                    <label for="nombre">Nombre: </label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $_POST["nombre"] ?>">   
                </div>

                <div class="campo">
                    <label for="tipo">Tipo: </label>
                    <select id="tipo" name="tipo">
                        <option value="" disabled selected hidden></option>
                        <option value="COMIDA" <?php if ($_POST['tipo'] == 'COMIDA') { echo 'selected'; } ?> >Comida</option>
                        <option value="BEBIDA" <?php if ($_POST['tipo'] == 'BEBIDA') { echo 'selected'; } ?> >Bebida</option>
                    </select>    
                </div>

                <div class="campo">
                    <label for="tipo">Orden Precio: </label>
                    <select id="orden" name="orden">
                        <option value="" disabled selected hidden></option>
                        <option value="ascendiente" <?php if ($_POST['orden'] == 'ascendiente') { echo 'selected'; } ?> >Ascendente</option>
                        <option value="descendente" <?php if ($_POST['orden'] == 'descendente') { echo 'selected'; } ?> >Descendente</option>
                    </select>
                </div>

                <div class="campo">
                    <input id="filtrar" type="submit" value="aplicar">
                </div>
            </fieldset>
        </form>

        
        <?php
            if ( isset($_SESSION['pedido_realizado']) ) {
                echo $_SESSION['pedido_realizado'];
                unset($_SESSION['pedido_realizado']);
            }
        ?>

        <div class="tabla">
            <div class="tabla-contenedor">
                <?php

                    // Conectamos a la base de datos
                    include "conectarDB.php";

                    // Seleccionamos los datos
                    $query = "SELECT * FROM items_menu where 1=1";


                    //Compruebo que el campo de formulario 'nombre' esta puesto
                    if(isset($_POST['nombre'])){
                        //Si es asi, creo una consulta SQL para obtener los datos por nombre
                        $query = $query . " AND nombre LIKE '%{$_POST['nombre']}%'";
                    }

                    //Compruebo que el campo de formulario 'tipo' esta puesto
                    if($_POST['tipo']){
                        //Si es asi, creo una consulta SQL para obtener los datos por tipo
                        $query = $query . " AND tipo = '{$_POST['tipo']}'";
                    }

                    
                    //Tomo el valor del campo de formulario "orden"
                    $orden = $_POST['orden'];

                    // Comprobamos si el valor del campo de formulario 'orden' es 'descendente'
                    if (isset($orden) && $orden == "descendente") {
                        // Si es así, creamos una consulta SQL para ordenar los resultados por precio en orden descendente
                        $query = $query . " ORDER BY precio DESC";
                    } else if (isset($orden) && $orden == "ascendiente"){
                        // Si no, creamos una consulta SQL para ordenar los resultados por precio en orden ascendente
                        $query = $query . " ORDER BY precio ASC";
                    }

                    // Ejecutamos la consulta SQL
                    $result = mysqli_query($conn, $query);
   
                    // Cerramos la conexión con la base de datos
                    mysqli_close($conn); 
                    
                    // Recorremos los resultados de la consulta SQL
                    while ($row = mysqli_fetch_array($result)) {
                        // Mostramos una tarjeta con la información del item del menú
                        ?><div class='card'>
                        <img src='mostrarImagen.php?id=<?php echo $row['id']?>' alt='pizza'>
                        <div class='card-texto'>
                        <p class='nombre'><?php echo $row['nombre']?></p>
                        <p class='tipo'><?php echo $row['tipo']?></p>
                        <p class='precio'><?php echo $row['precio']?></p>
                        </div>
                        </div><?php
                    }
                ?>
            </div>
        </div>

        <div id="filtrar" class="agregar">
            <button>  
                <a href="altapedido.php">Agregar nuevo pedido</a>
            </button>
        </div>

    </main>

    <footer class="footer">
        <h3>Integrantes del grupo</h3>
        <p>Juan Ignacio Coelho Soria, Taiel Alen Nunes, 2023</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
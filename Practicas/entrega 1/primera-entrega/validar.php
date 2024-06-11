<?php

if (isset($_POST['idItemMenu'])) {
    $idItemMenu = $_POST['idItemMenu'];
}
if (isset($_POST['nromesa'])) {
    $nromesa = $_POST['nromesa'];
}
if (isset($_POST['nromesa'])) {
    $comentarios = $_POST['comentarios'];
}


// Si los campos no estan vacios...
if (!empty($idItemMenu) && $idItemMenu !== "" && !empty($nromesa) && $nromesa !== "" ) {

    try {
        // Concetamos a la base de datos
        include "conectarDB.php";

        // Preparamos la consulta SQL para insertar los datos en la base de datos
        $pedido = "INSERT INTO pedidos (idItemMenu, nromesa, comentarios)
                   VALUES ('{$idItemMenu}', '{$nromesa}' , '{$comentarios}')";

        // Ejecutamos la consulta SQL
        $result = mysqli_query($conn, $pedido);

        if ( $result ) {
            // Almacenamos un mensaje en la sesión
            $_SESSION['pedido_realizado'] = '<p class="mensaje-exito">Se agregó un nuevo pedido a la lista de pedidos realizados</p>';

            // Redirigimos a la página index.php
            header('Location: index.php');
        }
    } catch (Exception $e) {
        // Mostramos un cartel de error
        $_SESSION['pedido_rechazado'] = '<p class="mensaje-error">SE PRODUJO UN ERROR AL INSERTAR EL PEDIDO.</p>';
    } finally {
        mysqli_close($conn);
    }
        
} else {

    if( empty($idItemMenu) && $idItemMenu === "" && !empty($nromesa) ) {
        $_SESSION['pedido_rechazado'] = '<p class="mensaje-error">EL CAMPO "COMIDA" ES OBLIGATORIO</p>';     
    }

    if( empty($nromesa) && $nromesa === "" && !empty($idItemMenu) ) {
        $_SESSION['pedido_rechazado'] = '<p class="mensaje-error">EL CAMPO "NUMERO DE MESA" ES OBLIGATORIO</p>';     
    }
}

?>
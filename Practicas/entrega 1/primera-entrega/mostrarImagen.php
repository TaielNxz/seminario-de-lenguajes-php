<?php

    // se recibe el valor que identifica la imagen en la tabla
    $id = $_GET['id']; 
  
    // Conectarse a la base de datos
    include "conectarDB.php";

    // Ejecutar la consulta SQL ( se recupera la información de la imagen )
    $query = "SELECT foto	
              FROM items_menu
              WHERE id={$id}";

    $result = mysqli_query($conn, $query); 

    // Obtener el resultado de la consulta
    $row = mysqli_fetch_array($result); 

    // Cerrar la conexión a la base de datos
    mysqli_close($conn); 

    // se imprime la imagen y se le avisa al navegador que lo que se está 
    // enviando no es texto, sino que es una imagen de un tipo en particular
    header("Content-type: jpg"); 
    echo $row['foto']; 

?>
<?php

// Declara las variables para la conexión
$hostname = "localhost";
$username = "root";
$password = "";
$database = "entrega1";

// Conecta a la base de datos
$conn = mysqli_connect($hostname, $username, $password, $database) or die ("Error " . mysqli_error($conn));

// Imprime un mensaje de éxito
// echo "Conexión a la base de datos exitosa!";

?>
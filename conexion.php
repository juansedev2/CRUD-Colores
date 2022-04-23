<?php
// Vamos a realizar la conexión a la base de datos con PDO
// Variables para la conexiòn
// El orden siempre debe ser: 1. tipo de SGBD y localización 2. Nombre de la base de datos
$lugar_bd = "mysql:host=127.0.0.1:3306;dbname=admin_color";
$usuario = "root";
$contra = "";

// Intentamos la conexión para validar si se efectua -- SIEMPRE REALIZARLO ASÍ
try{
    $conex= new PDO($lugar_bd, $usuario, $contra);
    //echo "Conexión a la base de datos de manera exitosa";
}catch(PDOException $error){
    echo "Falló la conexión debido a: ".$error;
}
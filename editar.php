<?php
include_once("conexion.php");
// Recibir los datos para actualizar
$id = $_GET["id"];
$color = $_GET["color"];
$descripcion = $_GET["descripcion"];

// Realizar consulta
$consulta = "update colores set color = ?, descripcion = ? where id = ?";
$consul = $conex->prepare($consulta);
$consul->execute(array($color, $descripcion, $id)); // Se le pasa los valores en orden
//echo "<h1> Valor de id = ".$id."</h1>";
header("location:index.php");
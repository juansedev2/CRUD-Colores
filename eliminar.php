<?php
// Incluimos la conexión
include_once("conexion.php");

$id = $_GET['id']; // Recibimos el id para realizar la filtración y consulta

// echo "El valor del id es: ".$id;
// Consulta
$consul_elimin = "delete from colores where id = ?";
$resul_elimin = $conex->prepare($consul_elimin);
$resul_elimin->execute(array($id));
header("location:index.php");

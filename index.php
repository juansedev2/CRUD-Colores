<?php
// Incluimos la conexión para hacer uso de la base de datos
include_once("conexion.php");

//Consulta a la base de datos - Sentencia sql
$sentencia = "Select * from colores";
$consul = $conex->prepare($sentencia); //Llamamos a la conexión y que prepare la consulta
$consul->execute(); //Ejecute la orden

// Leer: https://www.php.net/manual/es/pdostatement.fetchall
// Vamos a obtener y mostrar las filas obtenidas
// echo("Resultado de la consulta a la base de datos: "."<br>");
$resultado = $consul->fetchAll(); // Almacenamos el resultado a través de obtenerTodo
//print_r($resultado);

// Agregar un color
if (isset($_POST['submit'])) { // Verificar que se recibe la varible de botón
    $color = $_POST['color'];
    $descripcion = $_POST['descripcion'];
    if (strlen($color) == 0 or strlen($descripcion) == 0) { // Verificar que no esté vacio
        echo "<h2>Datos vacios, verifique por favor</h2>";
    } else {
        /*
        $sentenciaInsercion = "insert into colores(color, descripcion) values ($color, $descripcion)";
        $consul = $conex->prepare($sentenciaInsercion);
        $consult->execute(); 
        Esto es válido; pero el tema de inyecciones SQL está presente, así que según la doc
        con PDO, hacer: 
        https://www.php.net/manual/es/pdo.prepared-statements.php
        */
        $sentenciaInsercion = "insert into colores(color, descripcion) values (?, ?)";
        $consul = $conex->prepare($sentenciaInsercion);
        $consul->execute(array($color, $descripcion)); // Se le pasa los valores en orden para que los sustituya en la consulta
        // echo "<h2>Color insertado</h2>";
        header("location:index.php"); // Redirigir para el efecto de actualización
    }
}
// Editar un color
if ($_GET) {
    $dato = implode($_GET); // Convierte un arreglo en un string
    // Consultar a la bd
    $sentencia_edit = "select * from colores where id = ?";
    $consul = $conex->prepare($sentencia_edit);
    $consul->execute(array($dato)); //Ejecute la orden
    $resultado_edit = $consul->fetch(); // Almacenamos el resultado a través de obtener uno solo
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto de colores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <h1>Proyecto de colores</h1>
    <h2>Vamos a realizar un CRUD para ver colores de Bootstrap</h2>
    <div class="contenedor">
        <div class="filas_colores">
            <?php
            if ($_GET) : ?>
                <div class="contenedor2">
                    <div class="formulario">
                        <h1>Editar Color</h1>
                        <form method="GET" action="editar.php">
                            <input type="hidden" value="<?php echo $resultado_edit["id"] ?>" name="id">
                            <div class="seccionColor">
                                <label for="nombre">Nombre del color: </label>
                                <input type="text" name="color" value="<?php echo $resultado_edit["color"] ?>">
                            </div>
                            <div class="seccionDescripcion">
                                <label for="descripcion">Descripción del color: </label>
                                <textarea name="descripcion" id="descripcion" cols="30" rows="4" value="<?php echo $resultado_edit["descripcion"] ?>"></textarea>
                            </div>
                            <div class="botonEnvio">
                                <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                            </div>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <?php
                // Vamos a imprimir el resultado de la consulta
                /*Esta es una forma de hacer buen código PHP con HTML. El dos puntos significa
            que la sentencia queda abierta y debe continuar. Hasta ese punto, todo se repetirá*/
                foreach ($resultado as $registro) :
                ?>
                    <div class="alert alert-<?php print($registro["color"]); ?>" role="alert">
                        <?php echo "INDICE: " . $registro['id']; ?>
                        <h3>Alerta de Boostrap: <?php print($registro["color"]); ?> Descripción: <?php print $registro["descripcion"]; ?> &nbsp; <a href="index.php?id=<?php echo $registro["id"]; ?>" class="fas fa-pencil-alt xd" aria-hidden="true"></a>&nbsp;&nbsp;<a href="eliminar.php?id=<?php echo $registro['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                </svg></a></h3>
                    </div>
                <?php endforeach ?>
        </div>
    </div>
    <div class="contenedor2">
        <div class="formulario">
            <h1>Agregar otro color</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="seccionColor">
                    <label for="nombre">Nombre del color: </label>
                    <input type="text" name="color">
                </div>
                <div class="seccionDescripcion">
                    <label for="descripcion">Descripción del color: </label>
                    <textarea name="descripcion" id="descripcion" cols="30" rows="4"></textarea>
                </div>
                <div class="botonEnvio">
                    <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                </div>
            </form>
        </div>
    </div>

<?php endif ?>
</body>

</html>
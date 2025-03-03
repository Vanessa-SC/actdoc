<?php

/* Obtiene los datos de la constancia de un usuario en un curso */

// Conexión
include_once 'conexion.php';

// Recoge el ID del usuario 
if (!isset($_POST)) die();
$id = mysqli_real_escape_string($conn, $_POST['idUsuario']);

// Formato de idioma para las fechas
$formatt = "SET lc_time_names = 'es_MX' ";
mysqli_query($conn,$formatt);

// Consulta SQL
$sql = "SELECT curso.idCurso,
        curso.nombreCurso, 
        concat_ws(' - ', DATE_FORMAT(curso.fechaInicio, '%d de %M'), DATE_FORMAT(curso.fechaFin, '%d de %M, %Y')) as fecha,
        concat_ws(' - ',curso.horaInicio,curso.horaFin) as horario,
        DATE_FORMAT(curso.fechaInicio, '%d de %M %Y') as fechaExpedicion,
        curso.duracion,
        constancia.folio, 
        concat_ws(' ',instructor.apellidoPaterno,instructor.apellidomaterno,instructor.nombre) as maestro
        FROM usuario, curso, constancia, instructor
        WHERE constancia.Curso_idCurso = curso.idCurso
        AND constancia.Usuario_idUsuario = usuario.idUsuario
        AND curso.Instructor_idInstructor=instructor.idInstructor
        AND constancia.Usuario_idUsuario = $id
        AND constancia.rutaConstancia LIKE 'C_%'
        ORDER BY fechaInicio ASC
        ";

// Validación de ejecución de consulta
$result = $conn->query($sql) or die($conn->error . __LINE__);

// Declaración del array que contendrá los resultados de la consulta
$arr = array();

// Si hay resultados...
if ($result->num_rows > 0) {

    // Guardamos los resultados en el array
    while ($row = $result->fetch_assoc()) {
        $arr[]  = $row;
    }

}

// Impresión del array de resultados
echo json_encode($arr,true);
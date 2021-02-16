<?php

/*  Realiza una verificación para ver si todos los documentos del curso ya están validados o no  */

/* Conexión */
include_once 'conexion.php';

/* Recepción de datos */
$idc = mysqli_real_escape_string($conn, $_POST['idc']);

/* Array de respuesta */
$response = [];
$contador = 0;

/* Documentos necesarios para validar -> 1 2 3 7
Los primeros 3 se hacen en un ciclo para optimizar código.
 */
for ($i = 1; $i <= 3; $i++) {
/* SQL primeros 3*/
    $sql = "SELECT *
         FROM curso_has_documento
         WHERE Curso_idCurso = $idc
         AND Documento_idDocumento = $i
         AND estadoVerificado = 'SI'";

    // Ejecución de la consulta
    $result = $conn->query($sql) or die($conn->error . __LINE__);
    
    if (mysqli_num_rows($result) > 0) {
        $contador++;
    }
}

/* SQL documento 7 */
$sql7 = "SELECT *
         FROM curso_has_documento
         WHERE Curso_idCurso = $idc
         AND Documento_idDocumento = 7
         AND estadoVerificado = 'SI'";

// Ejecución de la consulta
$result7 = $conn->query($sql7) or die($conn->error . __LINE__);

if (mysqli_num_rows($result7) > 0) {
    $contador++;
}


$response['contador'] = $contador;
/* Contar si todos los documentos del curso han sido validados */
if ($contador == 4) {
    $response['docs_status'] = 'validos';
} else {
    $response['docs_status'] = 'invalidos';
}
// Imprimimos la respuesta
echo json_encode($response, true);

<?php

/* Registrará un nuevo instructor */

// Recepcion del array de datos
$inst = json_decode(file_get_contents("php://input"));
// Conexión
include_once('conexion.php');

/* Validar si hay Contrato */
if(empty($inst->contrato)){
    $inst->contrato = "Ninguno";
}

/* Validar si hay Horas */
if(empty($inst->horas)){
    $inst->horas = "Sin horas";
}

/* Validar si hay Departamento */
if(empty($inst->departamento)){
    $inst->departamento = 1;
}

/* Validar si hay FD */
if(empty($inst->funcionAdministrativa)){
    $inst->funcionAdministrativa = "NO";
}

/* Personal */
if ($inst->personal == 1) {
    $personal = "Interno";
}else {
    $personal = "Externo";
}

/* Conversión de los formatos de fecha */

setlocale(LC_TIME,'es_MX');

$fechaNacimiento = strftime ('%Y-%m-%d', strtotime($inst->fechaNacimiento));

/* Query de insercion  a tabla usuario */
$sql = "INSERT INTO usuario
        VALUES ('',
            '$inst->departamento',
            4,
            '".md5('tecno')."',
            '$inst->apellidoPaterno',
            '$inst->apellidoMaterno',
            '$inst->nombre',
            '$inst->sexo',
            '$inst->contrato',
            upper('$inst->RFC'),
            upper('$inst->CURP'),
            '$fechaNacimiento',
            '$inst->telefono',
            '$inst->correo',
            '$inst->horas',
            '$inst->nivel',
            '$inst->perfilDeseable',
            'SI',
            '$inst->funcionAdministrativa'
            )
        ";
// Ejecución del query y validación de su ejecucion
if (mysqli_query($conn, $sql)) {

    /* INSERTAR CAMPOS EN TABLA instructor */

    // Obtener el último ID insertado
    $idUsuario = mysqli_insert_id($conn);
    // Query de inserción a tabla instructor
    $sql = "INSERT INTO instructor
    VALUES(
        '',
        '$idUsuario',
        '$inst->apellidoPaterno',
        '$inst->apellidoMaterno',
        '$inst->nombre',
        upper('$inst->RFC'),
        upper('$inst->CURP'),
        '$fechaNacimiento',
        '$inst->telefono',
        '$inst->correo',
        '$personal'
    )";
    // Validación del éxito del segundo query
    if(mysqli_query($conn, $sql)){
        $response['status'] = 'ok';
    } else {
        $response['status'] = 'error al insertar instructor';
    }
} else {
    $response['status'] = 'error: ' . mysqli_error($conn);
} 
// Impresión del resultado
echo json_encode($response,JSON_FORCE_OBJECT);
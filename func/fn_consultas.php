<?php
/**
 * @return false|mysqli|null
 * funciones que permiten hacer consultas especificas a la base de datos
 * Fecha_creacion: 23/Julio/2021
 */

function CONEXION() {
    $CONEXION = mysqli_connect(getenv("DATABASE_HOSTNAME"), getenv("DATABASE_USER"), getenv("DATABASE_PASSWORD"), getenv("DATABASE_NAME"), getenv("DATABASE_PORT"))
                or die("Ha sucedido un error inexperado en la conexion de la base de datos");
    return $CONEXION;
}

function DESCONEXION($conexion) {
    $CLOSE = mysqli_close($conexion)
    or die("Ha sucedido un error inexperado en la desconexion de la base de datos");
    return $CLOSE;
}

function CODIFICACION() {
    // Consulta para la configuracion de la codificacion de caracteres
    $CONEXION = CONEXION();
    mysqli_query($CONEXION, "SET NAMES 'UTF8'");
}

function CONSULTAR($SQL) {
    $CONEXION = CONEXION();
    CODIFICACION();
    $RES = mysqli_query($CONEXION, $SQL);
    DESCONEXION($CONEXION);
    return $RES;
}

function ABC($SQL) {
    $CONEXION = CONEXION();
    $RES = mysqli_query($CONEXION, $SQL);
    DESCONEXION($CONEXION);
    return $RES;
}


<?php
$host = "localhost";
$usuario = "root";
$password = "";
$basedatos = "api";

$conexion = new mysqli($host, $usuario, $password, $basedatos);

if ($conexion->connect_error) {
    die("conexxión no establecida" . $conexion->connect_error);
}

?>
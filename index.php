<?php

require_once('./config.php');

header("Content-Type:application/json"); //nos va a apermitir devolver un archi json para consumir

$method = $_SERVER['REQUEST_METHOD'];

/* obtener el id de la url */
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
$buscar_id = explode('/', $path);
$id = ($path !== '/') ? end($buscar_id) : null;

printf($id);

switch ($method) {
    case 'GET':
        consultaSelect($conexion, $id);
        break;
    case 'POST':
        insertar($conexion);
        break;
    case 'PUT':
        actualizar($conexion, $id);
        break;
    case 'DELETE':
        borrar($conexion, $id, $buscar_id);
        break;
    default:
        echo "no hay metodo";
        break;
}

function consultaSelect($conexion_param,$id_param){
    if (isset($id_param)) {
        $sql = "select * from usuarios where id=$id_param";
        $resultado = $conexion_param->query($sql);
        if ($resultado) {
            if ($resultado) {
                $datos = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $datos[] = $fila;
                }
                echo json_encode($datos);
            }
        } else {
            echo json_encode(array('error' => 'error al eliminar usario'));
        }
    }else{
        $sql = "select * from usuarios";
        $resultado = $conexion_param->query($sql);
    
        if ($resultado) {
            $datos = array();
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
            echo json_encode($datos);
        }
    }
}

//función optimizada
/* function consultaSelect($conexion_param, $id_param) {
    $sql = "SELECT * FROM usuarios";
    
    if (isset($id_param)) {
        $sql .= " WHERE id = $id_param";
    }

    $resultado = $conexion_param->query($sql);

    if ($resultado) {
        $datos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        echo json_encode($datos);
    } else {
        echo json_encode(array('error' => 'Error en la consulta: ' . $conexion_param->error));
    }
} */


function insertar($conexion_param){
    $dato = json_decode(file_get_contents('php://input'), true); //obtener la información. nos van allegar los datos en json.
    $nombre = $dato['nombre'];
    print_r($nombre);

    $sql = "insert into usuarios(nombre) values('$nombre')";
    $resultado = $conexion_param->query($sql);

    if ($resultado) {
        $dato['id'] = $conexion_param->insert_id;
        echo json_encode($dato);
    } else {
        echo json_encode(array('error' => 'error al crear usuario'));
    }
}

function borrar($conexion_param, $id_param){
    //añadir opcion de si no exsite un id de usario
    $sql = "select * from usuarios where id=$id_param";
    $resultado = $conexion_param->query($sql);

    print_r($resultado->num_rows);

    $checkRow = $resultado->num_rows; //con esta variable compruebo si hay algun registro en la bd 1 existe 0 no existe

    if ($checkRow == 0) {
        echo json_encode(array('mensaje' => 'el ID proporcionado no existe'));
    } else {
        //borrado de usuario
        $sql = "DELETE FROM usuarios where id=$id_param";
        $resultado = $conexion_param->query($sql);

        if ($resultado) {
            echo json_encode(array('mensaje' => 'usario eliminado'));
        } else {
            echo json_encode(array('error' => 'error al eliminar usario'));
        }
    }
}

function actualizar($conexion_param, $id_param){
    $sql = "select * from usuarios where id=$id_param";
    $resultado = $conexion_param->query($sql);
    //print_r($resultado->num_rows);
    $checkRow = $resultado->num_rows; //con esta variable compruebo si hay algun registro en la bd 1 existe 0 no existe

    if ($checkRow == 0) {
        echo json_encode(array('mensaje' => 'el ID proporcionado no existe'));
    } else {

        $dato = json_decode(file_get_contents('php://input'), true); //obtener la información. nos van allegar los datos en json.
        $nombre = $dato['nombre'];
        //print_r($nombre);

        $sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id = '$id_param'";
        $resultado = $conexion_param->query($sql);

        if ($resultado) {
            echo json_encode(array('mensaje' => 'usario actualizado'));
        } else {
            echo json_encode(array('error' => 'error al actualizar usario'));
        }
    }
}

<?php
require_once "../controladores/tallas.controlador.php";
require_once "../modelos/tallas.modelo.php";
require_once "../controladores/colores.controlador.php";
require_once "../modelos/colores.modelo.php";

if (isset($_POST["action"]) && !empty($_POST["action"])) {
    $action = $_POST["action"];
    switch ($action) {
        case "mostrarTalla":

            $item = isset($_POST["itemTalla"]) ? $_POST["itemTalla"] : "";
            $valor = isset($_POST["valorTalla"]) ? $_POST["valorTalla"] : "";
            $respuesta = ControladorTallas::ctrMostrarTallas($item, $valor);
            echo json_encode($respuesta);
            break;

        case "mostrarColor":

            $itemColores = isset($_POST["itemColores"]) ? $_POST["itemColores"] : "";
            $valorColores = isset($_POST["valorColores"]) ? $_POST["valorColores"] : "";
            $respuestaColores = ControladorColores::ctrMostrarColores($itemColores, $valorColores);
            echo json_encode($respuestaColores);
            break;

    }
}

?>
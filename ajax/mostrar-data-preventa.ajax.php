<?php
require_once "../controladores/tallas.controlador.php";
require_once "../modelos/tallas.modelo.php";
require_once "../controladores/colores.controlador.php";
require_once "../modelos/colores.modelo.php";

if (isset($_GET["action"]) && !empty($_GET["action"])) {
    $action = $_GET["action"];
    switch ($action) {
        case "mostrarTalla":
            $item = isset($_GET["itemTalla"]) ? $_GET["itemTalla"] : "";
            $valor = isset($_GET["valorTalla"]) ? $_GET["valorTalla"] : "";
            $respuesta = ControladorTallas::ctrMostrarTallas($item, $valor);
            echo json_encode($respuesta);
            break;

        case "mostrarColor":
            $itemColores = isset($_GET["itemColores"]) ? $_GET["itemColores"] : "";
            $valorColores = isset($_GET["valorColores"]) ? $_GET["valorColores"] : "";
            $respuestaColores = ControladorColores::ctrMostrarColores($itemColores, $valorColores);
            echo json_encode($respuestaColores);
            break;
    }
}


?>
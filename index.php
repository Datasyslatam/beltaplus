<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/categorias.controlador.php";
require_once "controladores/subcategorias.controlador.php";
require_once "controladores/colores.controlador.php";
require_once "controladores/tallas.controlador.php";
require_once "controladores/motivos.controlador.php";
require_once "controladores/productos.controlador.php";
require_once "controladores/movimientos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/categorias.modelo.php";
require_once "modelos/subcategorias.modelo.php";
require_once "modelos/colores.modelo.php";
require_once "modelos/tallas.modelo.php";
require_once "modelos/motivos.modelo.php";
require_once "modelos/productos.modelo.php";
require_once "modelos/movimientos.modelo.php";

require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "extensiones/vendor/autoload.php";

$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();
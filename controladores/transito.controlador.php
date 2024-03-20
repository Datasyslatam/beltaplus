<?php

class ControladorTransito
{

    public static function ctrSumarProductosTransito()
    {
        if(isset($_POST['nuevaVenta']) && $_POST['listaProductos'] != ''){
            $data = $_POST['listaProductos'];
            ModeloTransito::mdlAgregarTransito($data);
        }
    }
    public static function ctrEliminarProductosTransito()
    {
        if(isset($_GET['idVenta']) && isset($_GET['devolucion'])){
            ModeloTransito::mdlRestarTransito($_GET['idVenta'], isset($_GET['devolucion']));
        }
        if(isset($_GET['pagado']) && isset($_GET['idVenta'])){
            $data = json_decode($_GET['productos']);
            $date = $_GET['date'];
            ModeloTransito::mdlEliminarVentaInProcess($data, $date);
        }
    }
}

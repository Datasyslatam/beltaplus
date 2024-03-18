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

        if(isset($_GET['idVenta'])){
            $idVenta = $_GET['idVenta'];
            ModeloTransito::mdlRestarTransito($idVenta, isset($_GET['devolucion']));
        }
        if(isset($_GET['pagado'])){
            ModeloTransito::mdlMoverRow($idVenta);
        }
    }
}

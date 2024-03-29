<?php

class ControladorTransito
{

    public static function ctrSumarProductosTransito()
    {
        if (isset($_POST['nuevaVenta']) && $_POST['listaProductos'] != '') {
            $data = $_POST['listaProductos'];
            ModeloTransito::mdlAgregarTransito($data);
        }
    }
    public static function ctrEliminarProductosTransito()
    {
        if (isset($_GET['idVenta']) && isset($_GET['devolucion'])) {
            $idVenta = ModeloTransito::mdlObtenerIdProceso($_GET['idVenta']);
            ModeloTransito::mdlRestarTransito($idVenta, isset($_GET['devolucion']));
            ModeloTransito::mdlEliminarVentaProceso($idVenta);
        }
        if (isset($_GET['pagado']) && isset($_GET['idVenta'])) {
            $idVenta = ModeloTransito::mdlObtenerIdProceso($_GET['idVenta']);
            ModeloTransito::mdlRestarTransito($idVenta, isset($_GET['devolucion']));
            ModeloTransito::mdlEliminarVentaProceso($idVenta);
        }
    }
    public static function ctrUnirVentas()
    {
        if (isset($_POST["nuevaVenta"])) {
            ModeloTransito::mdlConsultarUltimaInsercion();
        }
    }

    public static function ctrVerificarVentaEnProceso($idVenta){
        return ModeloTransito::mdlObtenerIdProceso($idVenta) != null;
    }

}

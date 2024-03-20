<?php
require_once 'conexion.php';

class ModeloTransito
{

    public static function mdlAgregarTransito($data)
    {
        $dataArray = json_decode($data, true);
        foreach ($dataArray as $item) {
            $codigoProducto = explode(' ', $item['descripcion'])[0];
            $stockEnTransito = intval($item['cantidad']);
            $stockActualTransito = self::obtenerStockTransito($codigoProducto);
            $stockActualProducto = self::obtenerStockProducto($codigoProducto);
            $nuevoStockEnTransito = $stockActualTransito + $stockEnTransito;
            $nuevoStockProducto = $stockActualProducto - $stockEnTransito;

            $sql = "UPDATE productos SET transito = :stockTransito, stock = :stockProducto WHERE codigo = :codigo";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(':stockTransito', $nuevoStockEnTransito);
            $stmt->bindParam(':stockProducto', $nuevoStockProducto);
            $stmt->bindParam(':codigo', $codigoProducto);
            $stmt->execute();
        }
    }

    public static function mdlRestarTransito($idTransito, $devolucion)
    {
        $data = json_decode(self::obtenerProductosTransito($idTransito), true);
        foreach ($data as $item) {
            $codigo = explode(' ', $item['descripcion'])[0];
            $stockEnTransito = intval($item['cantidad']);
            $stockActualTransito = self::obtenerStockTransito($codigo);
            $stockActualProducto = self::obtenerStockProducto($codigo);
            $nuevoStockEnTransito = $stockActualTransito - $stockEnTransito;
            $stockNuevoProducto = $stockEnTransito + $stockActualProducto;

            if ($nuevoStockEnTransito < 0) {
                $nuevoStockEnTransito = 0;
            }

            $sql = "UPDATE productos SET transito = :stock WHERE codigo = :codigo";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(':stock', $nuevoStockEnTransito);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->execute();

            if ($devolucion) {
                $sql = "UPDATE productos SET stock = :stock WHERE codigo = :codigo";
                $stmt = Conexion::conectar()->prepare($sql);
                $stmt->bindParam(':stock', $stockNuevoProducto);
                $stmt->bindParam(':codigo', $codigo);
                $stmt->execute();
            }
        }
    }
    public static function mdlEliminarVentaInProcess($productos, $date)
    {
        $origen = 'ventas_proceso';
        $sqlDelete = "DELETE FROM $origen WHERE productos = :productos AND fecha_venta = :fecha";
        $stmtDelete = Conexion::conectar()->prepare($sqlDelete);
        $stmtDelete->bindParam(':productos', $productos);
        $stmtDelete->bindParam(':fecha', $date);
        $stmtDelete->execute();
    }
    private static function obtenerStockTransito($codigo)
    {
        $sql = "SELECT transito FROM productos WHERE codigo = :codigo";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);
        return $stock['transito'];
    }

    private static function obtenerProductosTransito($idTransito)
    {
        $sql = "SELECT productos FROM ventas_proceso WHERE id = :idTransito";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':idTransito', $idTransito);
        $stmt->execute();
        $productos = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productos['productos'];
    }
    private static function obtenerStockProducto($codigo)
    {

        $sql = "SELECT stock FROM productos WHERE codigo = :codigo";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $productos = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productos['stock'];
    }
}

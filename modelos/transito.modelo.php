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
    public static function mdlConsultarUltimaInsercion()
    {
        $sql = "SELECT MAX(id) AS ultimaInsercion FROM ventas";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        $resultado1 = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT MAX(id) AS ultimaInsercion FROM ventas_proceso";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        $resultado2 = $stmt->fetch(PDO::FETCH_ASSOC);

        $idTabla1 = $resultado1['ultimaInsercion'];
        $idTabla2 = $resultado2['ultimaInsercion'];

        $sql = "INSERT INTO tabla_intermedia (idventa, idproceso) VALUES (:idTabla1, :idTabla2)";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':idTabla1', $idTabla1);
        $stmt->bindParam(':idTabla2', $idTabla2);
        $stmt->execute();
    }
    public static function mdlObtenerIdProceso($id)
    {
        $sql = "SELECT idproceso FROM tabla_intermedia WHERE idventa = :id";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['idproceso'];
    }
    public static function mdlEliminarVentaProceso($idProceso)
    {
        $sql = "DELETE FROM ventas_proceso WHERE id = :idProceso";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(':idProceso', $idProceso);
        $stmt->execute();
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

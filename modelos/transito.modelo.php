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
    public static function mdlMoverRow($idTransito)
    {
        $origen = 'ventas_proceso';
        $destino = 'ventas';
        $sqlSelect = "SELECT * FROM $origen WHERE id = :id";
        $stmtSelect = Conexion::conectar()->prepare($sqlSelect);
        $stmtSelect->bindParam(':id', $idTransito);
        $stmtSelect->execute();
        $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);
        $formatedRow = self::convertirTiposDeDatos($row);
        if ($formatedRow) {

            $id = self::obtenerUltimoId();
            $nuevoId = intval($id) + 1;
            $sqlInsert = "INSERT INTO $destino (id, codigo, id_cliente, id_vendedor, fecha_venta, productos, impuesto, neto, total, transportadora, metodo_pago, fecha)
            VALUES (:id, :codigo, :id_cliente, :id_vendedor, :fecha_venta, :productos, :impuesto, :neto, :total, :transportadora, :metodo_pago, :fecha)";
            $stmtInsert = Conexion::conectar()->prepare($sqlInsert);
            $stmtInsert->bindParam(':id', $nuevoId);
            $stmtInsert->bindParam(':codigo', $formatedRow['codigo']);
            $stmtInsert->bindParam(':id_cliente', $formatedRow['id_cliente']);
            $stmtInsert->bindParam(':id_vendedor', $formatedRow['id_vendedor']);
            $stmtInsert->bindParam(':fecha_venta', $formatedRow['fecha_venta']);
            $stmtInsert->bindParam(':productos', $formatedRow['productos']);
            $stmtInsert->bindParam(':impuesto', $formatedRow['impuesto']);
            $stmtInsert->bindParam(':neto', $formatedRow['neto']);
            $stmtInsert->bindParam(':total', $formatedRow['total']);
            $stmtInsert->bindParam(':transportadora', $formatedRow['transportadora']);
            $stmtInsert->bindParam(':metodo_pago', $formatedRow['metodo_pago']);
            $fecha = $formatedRow['fecha']->format('Y-m-d H:i:s');
            $stmtInsert->bindParam(':fecha', $fecha);
            $stmtInsert->execute();

            $sqlDelete = "DELETE FROM $origen WHERE id = :id";
            $stmtDelete = Conexion::conectar()->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $idTransito);
            $stmtDelete->execute();
        }
    }
    private static function convertirTiposDeDatos($fetchResult)
    {

        $fetchResult['id_cliente'] = intval($fetchResult['id_cliente']);
        $fetchResult['id_vendedor'] = intval($fetchResult['id_vendedor']);
        $fetchResult['codigo'] = intval($fetchResult['codigo']);
        $fetchResult['impuesto'] = floatval($fetchResult['impuesto']);
        $fetchResult['neto'] = floatval($fetchResult['neto']);
        $fetchResult['total'] = floatval($fetchResult['total']);
        $fetchResult['fecha'] = DateTime::createFromFormat('Y-m-d H:i:s', $fetchResult['fecha']);

        return $fetchResult;
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
    private static function obtenerUltimoId()
    {
        $sql = "SELECT MAX(id) AS max_id FROM ventas";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_id'];
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

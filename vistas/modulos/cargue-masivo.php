<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataCliente'])) {
    $tipo       = $_FILES['dataCliente']['type'];
    $tamanio    = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lineas     = file($archivotmp);
    $i = 0;

    foreach ($lineas as $linea) {

        if ($i != 0) {

            $datos = explode(";", $linea);

            $codigo = !empty($datos[1]) ? "'" . $datos[1] . "'" : "''";
            $color = !empty($datos[2]) ? $datos[2] : '';
            $categoria = !empty($datos[3]) ? $datos[3] : '';
            $talla = !empty($datos[5]) ? $datos[5] : '';
            $descripcion = !empty($datos[6]) ? $datos[6] : '';
            $stock = !empty($datos[7]) ? intval($datos[7]) : 0;
            $precioCompra = !empty($datos[9]) ? floatval($datos[9]) : 0.0;
            $precioVenta = !empty($datos[8]) ? floatval($datos[8]) : 0.0;

            $checkCodigoDuplicado = "SELECT codigo FROM productos WHERE codigo = :codigo";
            $stmtCheck = Conexion::conectar()->prepare($checkCodigoDuplicado);
            $stmtCheck->bindParam(':codigo', $codigo);
            $stmtCheck->execute();
            $cantDuplicados = $stmtCheck->rowCount();

            // No existe registros duplicados
            if ($cantDuplicados != 0) {
                $updateData = "UPDATE productos SET
                            stock = :stock,
                            precio_compra = :precioCompra,
                            precio_venta = :precioVenta
                            WHERE codigo = :codigo";
                $stmtUpdate = Conexion::conectar()->prepare($updateData);
                $stmtUpdate->bindParam(':stock', $stock);
                $stmtUpdate->bindParam(':precioCompra', $precioCompra);
                $stmtUpdate->bindParam(':precioVenta', $precioVenta);
                $stmtUpdate->bindParam(':codigo', $codigo);
                $stmtUpdate->execute();
            }
        }

        $i++;
    }
}
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
    window.location.href = 'movimientos';
    setTimeout(function() {
        Swal.fire({
            title: 'Cargue Masivo completado!',
            text: 'El cargue se ha completado correctamente',
            icon: 'success'
        });
    }, 4000);
</script>";
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataCliente'])) {
    $tipo       = $_FILES['dataCliente']['type'];
    $tamanio    = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lineas     = file($archivotmp);
    $i = 0;

    foreach ($lineas as $linea) {
        $cantidad_registros = count($lineas);
        $cantidad_regist_agregados = ($cantidad_registros - 1);

        if ($i != 0) {
            $datos = explode(";", $linea);
            $codigo = !empty($datos[1]) ? $datos[1] : '';
            $stock = !empty($datos[7]) ? intval($datos[7]) : 0; 
            $precxmayor = !empty($datos[8]) ? intval($datos[8]) : 0; 
            $precxund = !empty($datos[9]) ? intval($datos[9]) : 0; 

            // filtrado por el codigo para verificar duplicados
            $checkCodigoDuplicado = "SELECT codigo FROM productos WHERE codigo = :codigo ";
            $stmtCheck = Conexion::conectar()->prepare($checkCodigoDuplicado);
            $stmtCheck->bindParam(':codigo', $codigo);
            $stmtCheck->execute();
            $cantDuplicados = $stmtCheck->rowCount();

            // No existe registros duplicados actualizamos los campos de Stock, precio_compra y precio_venta tabla PRODUCTOS
            if ($cantDuplicados != 0) {
                $updateData = "UPDATE productos SET stock = :stock, precio_compra = :precio_compra, precio_venta = :precio_venta WHERE codigo = :codigo";
                $stmtUpdate = Conexion::conectar()->prepare($updateData);
                $stmtUpdate->bindParam(':stock', $stock);
                //$stmtUpdate->bindParam(':codigo', $codigo);
                $stmtUpdate->bindParam(':precio_compra', $precxmayor);
                $stmtUpdate->bindParam(':precio_venta', $precxund);
                $stmtUpdate->execute();
            }
        }

        $i++;
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

}else{
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    window.location.href = 'movimientos';
    <script>
        Swal.fire({
            title: 'Error en el Cargue!',
            text: 'No se ha seleccionado ningun archivo',
            icon: 'success'
        });
    </script>";
}

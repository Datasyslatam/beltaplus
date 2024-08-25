<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataCliente'])) {
    $tipo       = $_FILES['dataCliente']['type'];
    $tamanio    = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lineas     = file($archivotmp);
    $i = 0;

    // Conexión a la base de datos una sola vez
    $db = Conexion::conectar();

    // Excepciones para la conexión PDO
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        foreach ($lineas as $linea) {
            if ($i != 0) {
                $datos = explode(";", $linea);
                $codigo = !empty($datos[1]) ? $datos[1] : '';
                $categoria = !empty($datos[3]) ? $datos[3] : ''; 
                $subcategoria = !empty($datos[4]) ? $datos[4] : ''; 
                $color = !empty($datos[2]) ? $datos[2] : ''; 
                $talla = !empty($datos[5]) ? $datos[5] : ''; 
                $stock = !empty($datos[7]) ? intval($datos[7]) : 0; 
                $precio_compra = !empty($datos[8]) ? intval($datos[8]) : 0; 
                $precio_venta = !empty($datos[9]) ? intval($datos[9]) : 0;

                // Verificar si la categoría existe
                $checkCategoria = "SELECT id FROM categorias WHERE categoria = :categoria";
                $stmtCheckCat = $db->prepare($checkCategoria);
                $stmtCheckCat->bindParam(':categoria', $categoria);
                $stmtCheckCat->execute();

                if ($stmtCheckCat->rowCount() == 0) {
                    // La categoria no existe, se crea
                    $insertCategoria = "INSERT INTO categorias (categoria) VALUES (:categoria)";
                    $stmtInsertCat = $db->prepare($insertCategoria);
                    $stmtInsertCat->bindParam(':categoria', $categoria);
                    $stmtInsertCat->execute();
                    $categoria_id = $db->lastInsertId();
                } else {
                    $categoria_id = $stmtCheckCat->fetch(PDO::FETCH_ASSOC)['id'];
                }

                // Verificar si la subcategoría existe usando el `id_categoria` obtenido
                $checkSubCategoria = "SELECT id FROM subcategorias WHERE nombre = :subcategoria AND categoria_id = :categoria_id";
                $stmtCheckSubCat = $db->prepare($checkSubCategoria);
                $stmtCheckSubCat->bindParam(':subcategoria', $subcategoria);
                $stmtCheckSubCat->bindParam(':categoria_id', $categoria_id);
                $stmtCheckSubCat->execute();                

                if ($stmtCheckSubCat->rowCount() == 0) {
                    // La subcategoría no existe, se crea
                    $insertSubCategoria = "INSERT INTO subcategorias (nombre, categoria_id) VALUES (:subcategoria, :categoria_id)";
                    $stmtInsertSubCat = $db->prepare($insertSubCategoria);
                    $stmtInsertSubCat->bindParam(':subcategoria', $subcategoria);
                    $stmtInsertSubCat->bindParam(':categoria_id', $categoria_id);
                    $stmtInsertSubCat->execute();
                    $subcategoria_id = $db->lastInsertId();
                } else {
                    $subcategoria_id = $stmtCheckSubCat->fetch(PDO::FETCH_ASSOC)['id'];
                }
                

                // Verificar si el color existe
                $checkColor = "SELECT id FROM colores WHERE color = :color";
                $stmtCheckColor = $db->prepare($checkColor);
                $stmtCheckColor->bindParam(':color', $color);
                $stmtCheckColor->execute();

                if ($stmtCheckColor->rowCount() == 0) {
                    // El color no existe, se crea
                    $insertColor = "INSERT INTO colores (color) VALUES (:color)";
                    $stmtInsertColor = $db->prepare($insertColor);
                    $stmtInsertColor->bindParam(':color', $color);
                    $stmtInsertColor->execute();
                    $color_id = $db->lastInsertId();
                } else {
                    $color_id = $stmtCheckColor->fetch(PDO::FETCH_ASSOC)['id'];
                }

                // Verificar si la talla existe
                $checkTalla = "SELECT id FROM tallas WHERE talla = :talla";
                $stmtCheckTalla = $db->prepare($checkTalla);
                $stmtCheckTalla->bindParam(':talla', $talla);
                $stmtCheckTalla->execute();

                if ($stmtCheckTalla->rowCount() == 0) {
                    // La talla no existe, se crea
                    $insertTalla = "INSERT INTO tallas (talla) VALUES (:talla)";
                    $stmtInsertTalla = $db->prepare($insertTalla);
                    $stmtInsertTalla->bindParam(':talla', $talla);
                    $stmtInsertTalla->execute();
                    $talla_id = $db->lastInsertId();
                } else {
                    $talla_id = $stmtCheckTalla->fetch(PDO::FETCH_ASSOC)['id'];
                }

                // Verificar si el código de producto existe
                $checkCodigoDuplicado = "SELECT codigo FROM productos WHERE codigo = :codigo";
                $stmtCheck = $db->prepare($checkCodigoDuplicado);
                $stmtCheck->bindParam(':codigo', $codigo);
                $stmtCheck->execute();

                // Sino se crea con todo los parametros
                if ($stmtCheck->rowCount() == 0) {
                    $insertProducto = "INSERT INTO productos (codigo, id_categoria, id_subcategoria, id_color, id_talla, stock, precio_compra, precio_venta) 
                                       VALUES (:codigo, :categoria_id, :subcategoria_id, :color_id, :talla_id, :stock, :precio_compra, :precio_venta)";
                    $stmtInsertProd = $db->prepare($insertProducto);
                    $stmtInsertProd->bindParam(':codigo', $codigo);
                    $stmtInsertProd->bindParam(':categoria_id', $categoria_id);
                    $stmtInsertProd->bindParam(':subcategoria_id', $subcategoria_id);
                    $stmtInsertProd->bindParam(':color_id', $color_id);
                    $stmtInsertProd->bindParam(':talla_id', $talla_id);
                    $stmtInsertProd->bindParam(':stock', $stock);
                    $stmtInsertProd->bindParam(':precio_compra', $precio_compra);
                    $stmtInsertProd->bindParam(':precio_venta', $precio_venta);
                    $stmtInsertProd->execute();
                } else {
                    $updateData = "UPDATE productos SET stock = :stock, precio_compra = :precio_compra, precio_venta = :precio_venta WHERE codigo = :codigo";
                    $stmtUpdate = $db->prepare($updateData);
                    $stmtUpdate->bindParam(':stock', $stock);
                    $stmtUpdate->bindParam(':precio_compra', $precio_compra);
                    $stmtUpdate->bindParam(':precio_venta', $precio_venta);
                    $stmtUpdate->bindParam(':codigo', $codigo);
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
    } catch (Exception $e) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            window.location.href = 'movimientos';
            setTimeout(function() {
                Swal.fire({
                    title: 'Error en el Cargue!',
                    text: 'Ocurrió un error: " . $e->getMessage() . "',
                    icon: 'error'
                });
            }, 4000);
        </script>";
    }
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        window.location.href = 'movimientos';
        setTimeout(function() {
            Swal.fire({
                title: 'Error en el Cargue!',
                text: 'No se ha seleccionado ningun archivo',
                icon: 'error'
            });
        }, 4000);
    </script>";
}

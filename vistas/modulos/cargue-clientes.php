<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['dataCliente']) && $_FILES['dataCliente']['error'] === UPLOAD_ERR_OK) {
        $archivotmp = $_FILES['dataCliente']['tmp_name'];

        if (file_exists($archivotmp) && is_readable($archivotmp)) {
            $db = Conexion::conectar();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $file = fopen($archivotmp, "r");
                $i = 0;

                while (($data = fgetcsv($file, 10000000, ";")) !== FALSE) {
                    if ($i != 0) {
                        // Procesar datos
                        $mote = !empty($data[0]) ? $data[0] : '';
                        $apellido = !empty($data[1]) ? $data[1] : '';
                        $nombre = $mote . ' ' . $apellido;
                        $documento = !empty($data[7]) ? $data[7] : '';
                        $email = !empty($data[3]) ? $data[3] : '';
                        $telefono = !empty($data[2]) ? $data[2] : '';
                        $direccion = !empty($data[6]) ? $data[6] : '';
                        $localidad = !empty($data[4]) ? $data[4] : '';
                        $departamento = !empty($data[5]) ? $data[5] : '';
                        $ciudad = $localidad . ' - ' . $departamento;
                        $compras = '0';

                        if (empty($nombre) || empty($documento)) {
                            continue;
                        }

                        $checkDocumento = "SELECT documento FROM clientes WHERE documento = :documento";
                        $stmtCheckDoc = $db->prepare($checkDocumento);
                        $stmtCheckDoc->bindParam(':documento', $documento);
                        $stmtCheckDoc->execute();

                        if ($stmtCheckDoc->rowCount() == 0) {
                            $insertCliente = "INSERT INTO clientes (nombre, documento, email, telefono, direccion, ciudad, compras) VALUES (:nombre, :documento, :email, :telefono, :direccion, :ciudad, :compras)";
                            $stmtInsertClient = $db->prepare($insertCliente);
                            $stmtInsertClient->bindParam(':nombre', $nombre);
                            $stmtInsertClient->bindParam(':documento', $documento);
                            $stmtInsertClient->bindParam(':email', $email);
                            $stmtInsertClient->bindParam(':telefono', $telefono);
                            $stmtInsertClient->bindParam(':direccion', $direccion);
                            $stmtInsertClient->bindParam(':ciudad', $ciudad);
                            $stmtInsertClient->bindParam(':compras', $compras);
                            $stmtInsertClient->execute();
                        } else {
                            $updateData = "UPDATE clientes SET email = :email, telefono = :telefono, direccion = :direccion, ciudad = :ciudad WHERE documento = :documento";
                            $stmtUpdate = $db->prepare($updateData);
                            $stmtUpdate->bindParam(':email', $email);
                            $stmtUpdate->bindParam(':telefono', $telefono);
                            $stmtUpdate->bindParam(':direccion', $direccion);
                            $stmtUpdate->bindParam(':ciudad', $ciudad);
                            $stmtUpdate->bindParam(':documento', $documento);
                            $stmtUpdate->execute();
                        }
                    }
                    $i++;
                }

                fclose($file);

                echo '<script>
                    swal({
                        type: "success",
                        title: "Cargue Clientes completado!",
                        text: "El cargue se ha completado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "clientes";
                        }
                    });
                </script>';

            } catch (Exception $e) {
                echo '<script>
                    swal({
                        type: "error",
                        title: "Error en el Cargue!",
                        text: "Ocurrió un error: ' . addslashes($e->getMessage()) . '",
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "clientes";
                        }
                    });
                </script>';
            }

        } else {
            echo '<script>
                swal({
                    type: "error",
                    title: "Error en el Cargue!",
                    text: "El archivo no es accesible o no existe.",
                    showConfirmButton: true,
                    confirmButtonText: "Aceptar"
                }).then(function(result) {
                    if (result.value) {
                        window.location = "clientes";
                    }
                });
            </script>';
        }

    } else {
        echo '<script>
            swal({
                type: "error",
                title: "Error en el Cargue!",
                text: "No se ha seleccionado ningún archivo.",
                showConfirmButton: true,
                confirmButtonText: "Aceptar"
            }).then(function(result) {
                if (result.value) {
                    window.location = "clientes";
                }
            });
        </script>';
    }
}

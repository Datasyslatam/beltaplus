<?php
if ($_SESSION["perfil"] == "Especial") {
  echo '<script>
    window.location = "inicio";
  </script>';
  return;
}
?>

<div class="content-wrapper">
  <section class="content-header">

    <h1>

      Editar Venta Pendiente

    </h1>
    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Editar venta pendiente</li>

    </ol>
  </section>
  <section class="content">
    <div class="row">
      <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-5 col-xs-12">

        <div class="box box-success">

          <div class="box-header with-border"></div>
          <form role="form" method="post" class="formularioVenta">
            <div class="box-body">

              <div class="box">
                <?php
                $item = "id";
                $valor = $_GET["idVenta"];
                $venta = ControladorVentasTemp::ctrMostrarVentas($item, $valor);

                $itemUsuario = "id";
                $valorUsuario = $venta["id_vendedor"];
                $vendedor = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                $itemCliente = "id";
                $valorCliente = $venta["id_cliente"];
                $cliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
                //$porcentajeImpuesto = $venta["impuesto"] * 100 / $venta["neto"];

                $transporte = $venta["transportadora"];
                $fecha_venta = $venta["fecha_venta"];
                ?>
                <div class="box">
                  <!--=====================================
                DATOS DEL VENDEDOR
                ======================================-->

                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                      <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>
                      <input type="hidden" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>">
                      <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">
                    </div>
                  </div>
                  <!--=====================================
                CÓDIGO DE LA VENTA
                ======================================-->
                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="<?php echo $venta["codigo"]; ?>" readonly>
                      <!-- <input type="hidden" class="form-control" id="nuevaVenta" name="editarVenta" value="<?php echo $venta["codigo"]; ?>"> -->

                    </div>

                  </div>
                  <!--=====================================
                DATOS DEL CLIENTE
                ======================================-->
                  <div class="form-group">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="hidden" class="form-control" id="seleccionarCliente" name="seleccionarCliente" value="<?php echo $cliente["id"]; ?>" readonly>
                      <input type="input" class="form-control" id="mostrarCliente" name="mostrarCliente" value="<?php echo $cliente["nombre"]; ?>" readonly>

                    </div>

                  </div>
                  <!-- ENTRADA PARA LA FECHA DE PEDIDO Dato nuevo-->
                  <label for="">Fecha de venta</label>
                  <div class="input-group">

                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input value="<?php echo ($fecha_venta); ?>" type="date" class="form-control input-xs" name="nuevaFechaVenta" id="nuevaFechaVenta" required>
                    </div>
                  </div>
                  <hr>

                  <!--=====================================
                LISTA DE PRODUCTOS PENDIENTES
                ======================================-->
                  <div class="form-group row nuevoProducto productosTemporales">
                    <?php
                    $listaProducto = json_decode($venta["productos"], true);
                    foreach ($listaProducto as $key => $value) {
                      $item = "id";
                      $valor = $value["id"];
                      $orden = "id";
                      $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
                      $stockAntiguo = $respuesta["stock"] + $value["cantidad"];
                      $descripcion = $value['descripcion'];
                      $partes = explode(' ', $descripcion);
                      $codigoProducto = $partes[0];
                      echo '<div class="row" style="padding:5px 15px">
                          <input type="hidden" id="tempproduct">
                          <div class="col-xs-6" style="padding-right:0px">
              
                            <div class="input-group">
                  
                              <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="' . $value["id"] . '"><i class="fa fa-times"></i></button></span>
                              <input type="text" class="form-control nuevaDescripcionProducto" idProducto="' . $value["id"] . '" name="agregarProducto" value="' . $value["descripcion"] . '" readonly required>
                              <input type="hidden" class="form-control nuevaDescripcionProducto" idProducto="' . $value["id"] . '" name="agregarProducto" value="' . $value["descripcion"] . '" required>
                            </div>
                          </div>
                          <div class="col-xs-3">

                            <input type="number" class="form-control nuevaCantidadProducto" id="' . $codigoProducto . '" name="nuevaCantidadProducto" min="1" value="' . $value["cantidad"] . '" stock="' . $stockAntiguo . '" nuevoStock="' . $value["stock"] . '" required>
                          </div>
                          <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                    
                              <input type="hidden" class="form-control nuevoPrecioProducto" precioReal="' . $respuesta["precio_venta"] . '" name="nuevoPrecioProducto" value="' . $value["total"] . '" required>
                              <input type="text" class="form-control nuevoPrecioProducto" precioReal="' . $respuesta["precio_venta"] . '" name="nuevoPrecioProducto" value="' . $value["total"] . '" readonly required>
    
                            </div>
                
                          </div>
                        </div>';
                    }
                    ?>
                  </div>

                  <input type="hidden" id="listaProductos" name="listaProductos">

                  <hr>
                  <div class="row">
                    <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->
                    <div class="col-xs-8 pull-right">

                      <table class="table">
                        <thead>
                          <tr>
                            <th>Impuesto</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>

                          <tr>

                            <td style="width: 50%">

                              <div class="input-group">

                                <!-- <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="<?php echo $porcentajeImpuesto; ?>" required> -->
                                <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="<?php echo ($venta["impuesto"] / $venta["neto"]) * 100; ?>" required>
                                <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" value="<?php echo $venta["impuesto"]; ?>" required>
                                <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" value="<?php echo $venta["neto"]; ?>" required>
                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                              </div>
                            </td>
                            <td style="width: 50%">

                              <div class="input-group">

                                <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="<?php echo $venta["neto"]; ?>" value="<?php echo $venta["total"]; ?>" readonly required>
                                <input type="hidden" name="totalVenta" value="<?php echo $venta["total"]; ?>" id="totalVenta">

                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <hr>
                  <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->
                  <div class="form-group row">

                    <div class="col-xs-6" style="padding-right:0px">

                      <div class="input-group">

                        <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" required>
                          <option value="">Seleccione método de pago</option>
                          <option value="Efectivo">Efectivo</option>
                          <option value="TC">Tarjeta Crédito</option>
                          <option value="TD">Tarjeta Débito</option>
                          <option value="TB">Transferencia Bancaria</option>
                          <option value="NQ">Nequi</option>
                        </select>
                      </div>
                    </div>
                    <div class="cajasMetodoPago"></div>
                    <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">
                  </div>
                  <br>
                  <script>
                    $(document).ready(function() {
                      let codigoTransaccion = <?php echo json_encode($venta["metodo_pago"]); ?>;

                      if (codigoTransaccion != 'Efectivo') {
                        let partesCodigoTransaccion = codigoTransaccion.split('-');
                        let prefijo = partesCodigoTransaccion[0];
                        let numeroTransaccion = partesCodigoTransaccion[1];
                        $("#nuevoMetodoPago").val(prefijo).trigger('change');
                        $('#nuevoCodigoTransaccion').val(numeroTransaccion);
                      } else {
                        $("#nuevoMetodoPago").val('Efectivo').trigger('change');
                      }
                    });
                  </script>


                  <!--=====================================
                ENTRADA TRANSPORTADORA
                ======================================-->

                  <div class="form-group row">

                    <div class="col-xs-6" style="padding-right:0px">

                      <div class="input-group">
                        <textarea maxlength="120" name="nuevaTransporta" id="nuevaTransporta" cols="50" rows="2"><?php echo $transporte; ?></textarea>
                      </div>
                    </div>
                    <div class="cajasMetodoPago"></div>
                    <input type="hidden" id="nuevaTransportad" name="nuevaTransportad">
                  </div>

                </div>
              </div> <!--Class="box" -->
            </div>

            <div class="box-footer">
              <!-- <button type="submit" class="btn btn-warning pull-right">Pendiente</button>
              <button type="submit" class="btn btn-success pull-right">Facturar venta</button> -->

              <span class="input-group-addon">
                <button type="submit" class="btn btn-warning pull-left" name="acciontemp" value="actualizar">
                  <i class="fa fa-bell-o"></i> Actualizar Pendiente
                </button>
                <button type="submit" class="btn btn-success pull-right" name="acciontemp" value="facturar">
                  <i class="fa fa-address-card-o"></i> Facturar Venta
                </button>
              </span>
            </div>

          </form>
          <button style="display: none;" id="getstock">obtener stock</button>
          <?php
          // $guardarVenta = new ControladorVentas();
          // $guardarVenta->ctrCrearVenta();

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["acciontemp"])) {
              $accion = $_POST["acciontemp"];

              // Dependiendo de la acción seleccionada, utiliza el controlador apropiado
              switch ($accion) {
                case 'actualizar':
                  $guardarVenta = new ControladorVentasTemp();
                  $guardarVenta->ctrEliminarVenta();
                  $guardarVenta->ctrCrearVenta();
                  break;
                case 'facturar':
                  $guardarVenta = new ControladorVentas();
                  $guardarVenta->ctrCrearVenta();
                  $eliminarCarrito = new ControladorVentasTemp();
                  $eliminarCarrito->ctrEliminarVenta(false);
                  break;
                default:
                  echo '<script>console.log("ayuda por favo");</script>';
                  break;
              }
            }
          }
          ?>
        </div>

      </div>
      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->
      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">

        <div class="box box-warning">

          <!-- FILTRO DE PRODUCTOS -->
          <div class="filter-section mb-3" style="margin: 20px;">
            <h4 class="mb-2">Panel de filtro de productos</h4>
            <div class="input-group-small mb-3">
              <div class="input-group input-filter-text filterSearch hidden" data-id="categorias" id="cont-categorias">
                <input type="text" class="form-control" placeholder="Filtro de categoria" id="categorias" disabled>
                <button class="btn btn-danger eliminar-filtro" type="button"><i class="fa fa-times"></i></button>
              </div>
              <div class="input-group input-filter-text filterSearch hidden" data-id="subcategoria" id="cont-subcategoria">
                <input type="text" class="form-control" placeholder="Filtro de subcategoria" id="subcategoria" disabled>
                <button class="btn btn-danger eliminar-filtro" type="button"><i class="fa fa-times"></i></button>
              </div>
              <div class="input-group input-filter-text filterSearch hidden" data-id="colores" id="cont-colores">
                <input type="text" class="form-control" placeholder="Filtro de colores" id="colores" disabled>
                <button class="btn btn-danger eliminar-filtro" type="button"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="btn-group-separable d-flex" role="group" id="button-group">

            </div>

          </div>

          <div class="box-header with-border"></div>
          <div class="box-body">

            <table class="table table-bordered table-striped dt-responsive tablaVentas">

              <thead>
                <tr>
                  <th style="width: 10px">Código</th>
                  <th>Categoria</th>
                  <th>SubCategoria</th>
                  <th>Color</th>
                  <th>Talla</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>
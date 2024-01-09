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

      Crear venta

    </h1>
    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Crear venta</li>

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
                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>
                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">
                  </div>
                </div>
                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================-->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <?php
                    $item = null;
                    $valor = null;
                    $ventas = ControladorVentas::ctrMostrarVentas($item, $valor);
                    if (!$ventas) {
                      echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="10001" readonly>';
                    } else {
                      foreach ($ventas as $key => $value) {
                      }
                      $codigo = $value["codigo"] + 1;
                      echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' . $codigo . '" readonly>';
                    }
                    ?>
                  </div>

                </div>
                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================-->
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

                    <select class="form-control" id="seleccionarCliente" name="seleccionarCliente" required>
                      <option value="">Seleccionar cliente</option>
                      <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);
                      foreach ($categorias as $key => $value) {
                        echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                      }
                      ?>
                    </select>

                    <span class="input-group-addon">
                      <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">
                        <i class="fa fa-user"></i> Agregar
                      </button>
                      <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalFiltrarCliente" data-dismiss="modal">
                        <i class="fa fa-search"></i> Buscar
                      </button>
                    </span>

                  </div>

                </div>
                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->
                <div class="form-group row nuevoProducto">

                </div>
                <input type="hidden" id="listaProductos" name="listaProductos">
                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->
                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>
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

                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" placeholder="0" required>
                              <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>
                              <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>
                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                            </div>
                          </td>
                          <td style="width: 50%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                              <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="00000" readonly required>
                              <input type="hidden" name="totalVenta" id="totalVenta">

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

                <!--=====================================
                ENTRADA TRANSPORTADORA
                ======================================-->

                <div class="form-group row">

                  <div class="col-xs-6" style="padding-right:0px">

                    <div class="input-group">
                      <textarea maxlength="120" name="nuevaTransporta" id="nuevaTransporta" cols="50" rows="2" placeholder="Digite el Nombre de la EMpresa de Transporte"></textarea>
                    </div>
                  </div>
                  <div class="cajasMetodoPago"></div>
                  <input type="hidden" id="nuevaTransportad" name="nuevaTransportad">
                </div>
                <br>

              </div>
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-primary pull-right">Guardar venta</button>
            </div>
          </form>
          <?php
          $guardarVenta = new ControladorVentas();
          $guardarVenta->ctrCrearVenta();

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

<!--=====================================
MODAL AGREGAR CLIENTE VENTAS
======================================-->
<div id="modalAgregarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" id="crear-nuevo-cliente">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar cliente</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de cliente</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control input-lg" name="nuevoCliente" placeholder="Ingresar nombre" id="nuevoCliente" required>
              </div>
            </div>
            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            <label for="">Documento de identidad</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" id="nuevoDocumentoId" placeholder="Ingresar documento" required>
              </div>
            </div>
            <!-- ENTRADA PARA EL EMAIL -->
            <label for="">Email</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email" required>
              </div>
            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
            <label for="">Teléfono</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                <input type="text" class="form-control input-lg" name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>
              </div>
            </div>
            <!-- ENTRADA PARA LA DIRECCIÓN -->
            <label for="">Dirección</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                <input type="text" class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA CIUDAD -->
            <label for="">Ciudad</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                <input type="text" class="form-control input-lg" name="nuevaCiudad" id="nuevaCiudad" placeholder="Ingresar ciudad de residencia" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            <label for="">Fecha de nacimiento</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="date" class="form-control input-lg" name="nuevaFechaNacimiento" id="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" required>
              </div>
            </div>

          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal" onclick="limpiarCampoModalCliente()">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar cliente</button>
        </div>
      </form>
      <?php
      # Guardar clientes
      /* $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente(); */
      ?>
    </div>
  </div>
</div>



<!--=====================================
MODAL FILTRAR CLIENTE
======================================-->
<div id="modalFiltrarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" id="filtrar-cliente">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Buscar cliente</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            <label for="">Documento de identidad</label>
            <input type="hidden" id="idClienteSolicitado">
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="number" min="0" class="form-control input-lg" name="filtrarDocumento" id="filtrarDocumento" placeholder="Ingresar documento">
              </div>
            </div>
            <!-- ENTRADA PARA EL EMAIL -->
            <label for="">Email</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control input-lg" name="filtrarEmail" id="filtrarEmail" placeholder="Ingresar email">
              </div>
            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
            <label for="">Teléfono</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                <input type="text" class="form-control input-lg" name="filtrarTelefono" id="filtrarTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
            </div>

            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de cliente</label>
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control input-lg" name="filtrarNombre" id="filtrarNombre" placeholder="Ingresar nombre" readonly>
              </div>
            </div>

          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal" onclick="limpiarCampoModalCliente()">Salir</button>
          <button type="submit" class="btn btn-primary">Filtrar cliente</button>
        </div>
      </form>
    </div>
  </div>
</div>

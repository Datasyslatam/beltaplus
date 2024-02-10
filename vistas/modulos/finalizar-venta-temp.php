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
                ?>

                <div class="box">
                <!--=====================================
                DATOS DEL VENDEDOR
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>
                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">
                  </div>
                </div>
                <!--=====================================
                CÓDIGO DE LA VENTA
                ======================================-->
                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                   <input type="text" class="form-control" id="nuevaVenta" name="editarVenta" value="<?php echo $venta["codigo"]; ?>" readonly>
               
                  </div>
                
                </div>
                <!--=====================================
                DATOS DEL CLIENTE
                ======================================-->
                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <input type="text" class="form-control" id="nuevoCliente" name="editarCliebte" value="<?php echo $cliente["nombre"]; ?>" readonly>
               
                  </div>
                
                </div>


                <!--=====================================
                LISTA DE PRODUCTOS PENDIENTES
                ======================================-->
                <div class="form-group row nuevoProducto">
                  <?php
                  $listaProducto = json_decode($venta["productos"], true);
                  foreach ($listaProducto as $key => $value) {
                    $item = "id";
                    $valor = $value["id"];
                    $orden = "id";
                    $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
                    $stockAntiguo = $respuesta["stock"] + $value["cantidad"];
                    
                    echo '<div class="row" style="padding:5px 15px">
              
                          <div class="col-xs-6" style="padding-right:0px">
              
                            <div class="input-group">
                  
                              <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="'.$value["id"].'"><i class="fa fa-times"></i></button></span>
                              <input type="text" class="form-control nuevaDescripcionProducto" idProducto="'.$value["id"].'" name="agregarProducto" value="'.$value["descripcion"].'" readonly required>
                            </div>
                          </div>
                          <div class="col-xs-3">
                
                            <input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'.$stockAntiguo.'" nuevoStock="'.$value["stock"].'" required>
                          </div>
                          <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                    
                              <input type="text" class="form-control nuevoPrecioProducto" precioReal="'.$respuesta["precio_venta"].'" name="nuevoPrecioProducto" value="'.$value["total"].'" readonly required>
    
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
                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="<?php echo $venta["impuesto"]; ?>" required readonly>
                               <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" value="<?php echo $venta["impuesto"]; ?>" required>
                               <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" value="<?php echo $venta["neto"]; ?>" required>
                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        
                            </div>
                          </td>
                           <td style="width: 50%">
                            
                            <div class="input-group">
                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                              <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="<?php echo $venta["neto"]; ?>"  value="<?php echo $venta["total"]; ?>" readonly required>
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

               </div>
              </div> <!--Class="box" -->
            </div>
            
            <div class="box-footer">
              <!-- <button type="submit" class="btn btn-warning pull-right">Pendiente</button>
              <button type="submit" class="btn btn-success pull-right">Facturar venta</button> -->

              <span class="input-group-addon">
                <button type="" class="btn btn-warning pull-left">
                    <i class="fa fa-bell-o"></i> Actualizar Pendiente
                </button>
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-address-card-o"></i> Facturar Venta
                </button>
              </span>
            </div>
            
          </form>
          <?php
          $guardarVenta = new ControladorVentasTemp();
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

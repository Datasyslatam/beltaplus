<?php
if($_SESSION["perfil"] == "Vendedor"){
  echo '<script>
    window.location = "inicio";
  </script>';
  return;
}
?>
<div class="content-wrapper">
  <section class="content-header">
    
    <h1>
      
      Administrar movimientos
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar movimientos</li>
    
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
  
       <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMovimiento">
          
          Agregar movimiento
        </button>-->
      </div>
      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaMovimientos" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Imagen</th>
           <th>Código</th>
           <th>Descripción</th>
           <th>Categoría</th>
           <th>Stock</th>
           <th>Agregado</th>
           <th>Acciones</th>
           
         </tr> 
        </thead>      
       </table>
       <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
      </div>
    </div>
  </section>
</div>
<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->
<div id="modalIngresoMovimiento" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registrar ingreso de productos</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
           <!-- ENTRADA PARA LA DESCRIPCIÓN -->
             <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 
                <input type="text" class="form-control input-lg" id="ingresoDescripcion" name="ingresoDescripcion" placeholder="Ingresar descripción" required readonly>
              </div>
            </div>
             <div class="form-group row">
              <div class="col-md-6">
              <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
              <label for="" class="">Motivo</label>
              <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="nuevoMotivo" name="ingresoMotivo" required>
                  
                  <option value="">Selecionar motivo</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $motivos = ControladorMotivos::ctrMostrarMotivos($item, $valor);
                  foreach ($motivos as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["motivo"].'</option>';
                  }
                  ?>
  
                </select>
              </div>
            </div>
              </div>
             <div class="col-md-6">
             <!-- ENTRADA PARA STOCK -->
             <label for="" class="">Movimiento de entrada</label>
             <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 
                <input type="number" class="form-control input-lg" name="ingresoStock" min="0" placeholder="Stock" required>
              </div>
            </div>
              </div>
            </div>

          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
        <input type="hidden" id="ingresoId" name="ingresoId">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar movimiento</button>
        </div>
      </form>
        <?php
          $ingresoMovimiento = new ControladorMovimientos();
          $ingresoMovimiento -> ctrIngresoMovimiento();
        ?>  
    </div>
  </div>
</div>


<!--=====================================
MODAL SALIDA PRODUCTO
======================================-->
<div id="modalSalidaMovimiento" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registrar salida de productos</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
             <!-- ENTRADA PARA LA DESCRIPCIÓN -->
             <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 
                <input type="text" class="form-control input-lg" id="salidaDescripcion" name="salidaDescripcion" placeholder="Ingresar descripción" required readonly>
              </div>
            </div>
             <div class="form-group row">
              <div class="col-md-6">
              <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
              <label for="" class="">Motivo</label>
              <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="salidaMotivo" name="salidaMotivo" required>
                  <option value="">Selecionar motivo</option>
                  <?php
                 $item = null;
                  $valor = null;
                  $motivos = ControladorMotivos::ctrMostrarMotivos($item, $valor);
                  foreach ($motivos as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["motivo"].'</option>';
                  }
                  ?>
  
                </select>
              </div>
            </div>
              </div>
             <div class="col-md-6">
             <!-- ENTRADA PARA MOVIMIENTO -->
             <label for="" class=""> Cantidad Movimiento </label>
             <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 
                <input type="number" class="form-control input-lg" id="salidaStock" name="salidaStock" min="0" placeholder="Cantidad" required>
              </div>
            </div>
              </div>
            </div>

          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
        <input type="hidden" id="salidaId" name="salidaId">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Registrar salida</button>
        </div>
      </form>
        <?php
          $salidaMovimiento = new ControladorMovimientos();
          $salidaMovimiento -> ctrSalidaMovimiento();
        ?>      
    </div>
  </div>
</div>

<?php
  $eliminarMovimiento = new ControladorMovimientos();
  $eliminarMovimiento -> ctrEliminarMovimiento();
?>      

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
    <h1> Administrar productos </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar productos</li>
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <button class="btn btn-primary me-2" data-toggle="modal" data-target="#modalAgregarProducto"> <i class="fa fa-plus"></i> Agregar producto </button>
        <button class="btn btn-success" onclick="location.href = 'filtrop'"> <i class="fa fa-filter"></i> Filtrar Productos</button>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablaProductos" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Código</th>
              <th>Descripción</th>
              <th>Categoría</th>
         <!--      <th>Subcategoría</th> -->
              <th>Stock</th>
              <th>Precio Unitario</th>
              <th>Precio Mayorista</th>
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
<div id="modalAgregarProducto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar producto</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body"> 
            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
            <label for="">Categoría</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" id="nuevaCategoria" name="nuevaCategoria" required>
                  <option value="">Selecionar categoría</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- ENTRADA PARA SELECCIONAR SUBCATEGORÍA -->
            <label for="">Subcategoría</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                <select class="form-control input-lg" id="nuevaSubCategoria" name="nuevaSubCategoria" required>
                  <option value="">Seleccionar Subcategoría</option>

                  <?php
                  $item = null;
                  $valor = null;
                  $subcategorias = ControladorSubCategorias::ctrMostrarSubCategorias($item, $valor);
                  foreach ($subcategorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- ENTRADA PARA SELECCIONAR COLOR -->
            <label for="">Color</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                <select class="form-control input-lg" id="nuevoColor" name="nuevoColor" required>
                  <option value="">Seleccionar Color</option>
                  <option value="1">Cafe tierra</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $categorias = ControladorColores::ctrMostrarColores($item, $valor);
                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["color"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <!-- ENTRADA PARA SELECCIONAR TALLA -->
            <label for="">Talla</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                <select class="form-control input-lg" id="nuevaTalla" name="nuevaTalla" required>
                  <option value="">Seleccionar Talla</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $categorias = ControladorTallas::ctrMostrarTallas($item, $valor);
                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["talla"].'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <!-- ENTRADA PARA EL CÓDIGO -->
            <label for="">Código</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-code"></i></span>
                <input type="text" class="form-control input-lg" id="nuevoCodigo" name="nuevoCodigo" placeholder="Ingresar código" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <label for="">Descripción</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-audio-description"></i></span>
                <input type="text" class="form-control input-lg" name="nuevaDescripcion" placeholder="Ingresar descripción" required>
              </div>
            </div>
            <!-- ENTRADA PARA STOCK -->
            <label for="">Stock</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-stack-overflow"></i></span>
                <input type="number" class="form-control input-lg" name="nuevoStock" min="0" placeholder="Stock" required>
              </div>
            </div>
            
            <!-- ENTRADA PARA PRECIO COMPRA -->
            <div class="form-group row">
              <div class="col-xs-6">
                <label for="">Precio Unitario</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                  <input type="number" class="form-control input-lg" id="nuevoPrecioCompra" name="nuevoPrecioCompra" step="any" min="0" required>
                </div>
              </div>
              <div class="col-xs-6"> 
                <!-- ENTRADA PARA PRECIO VENTA -->
                <label for="">Precio Mayorista</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                  <input type="number" class="form-control input-lg" id="nuevoPrecioVenta" name="nuevoPrecioVenta" step="any" min="0" required>
                </div>
              </div>
            </div>
            <div class="form-group row"> 
              <!-- CHECKBOX PARA PORCENTAJE -->
              <div class="col-xs-6">
                <label>Usar porcentaje</label>
                <div class="form-group">
                  <input type="checkbox" class="minimal porcentaje" checked>
                  Utilizar procentaje </div>
              </div>
              <!-- ENTRADA PARA PORCENTAJE -->
              <div class="col-xs-6">
                <div class="input-group">
                  <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>
                  <span class="input-group-addon"><i class="fa fa-percent"></i></span> </div>
              </div>
            </div>
            
            <!-- ENTRADA PARA SUBIR FOTO -->
            <label for="">Imagen producto</label>
            <div class="form-group">
              <div class="panel">SUBIR IMAGEN</div>
              <input type="file" class="nuevaImagen" name="nuevaImagen">
              <p class="help-block">Peso máximo de la imagen 2MB</p>
              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px"> </div>
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar producto</button>
        </div>
      </form>
      <?php
          $crearProducto = new ControladorProductos();
          $crearProducto -> ctrCrearProducto();
        ?>
    </div>
  </div>
</div>


<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->
<div id="modalEditarProducto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" enctype="multipart/form-data">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar producto</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body"> 
            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
            <label for="">Categoría</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg"  name="editarCategoria" readonly required>
                  <option id="editarCategoria"></option>
                </select>
              </div>
            </div>
             <!-- ENTRADA PARA SELECCIONAR SUBCATEGORÍA -->
            <label for="">Subcategoría</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg"  name="editarSubCategoria" readonly required>
                  <option id="editarSubCategoria"></option>
                </select>
              </div>
            </div>
            <!-- ENTRADA PARA EL CÓDIGO -->
            <label for="">Código</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-code"></i></span>
                <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>
              </div>
            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <label for="">Descripción</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion" required>
              </div>
            </div>
            <!-- ENTRADA PARA STOCK -->
            <label for="">Stock</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="number" class="form-control input-lg" id="editarStock" name="editarStock" min="0" required>
              </div>
            </div>
            
            <!-- ENTRADA PARA PRECIO COMPRA -->
            <div class="form-group row">
              <div class="col-xs-6">
                <label for="">Precio Unitario</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                  <input type="number" class="form-control input-lg" id="editarPrecioCompra" name="editarPrecioCompra" step="any" min="0" required>
                </div>
              </div>
              <div class="col-xs-6"> 
                <!-- ENTRADA PARA PRECIO VENTA -->
                <label for="">Precio Mayorista</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                  <input type="number" class="form-control input-lg" id="editarPrecioVenta" name="editarPrecioVenta" step="any" min="0" readonly required>
                </div>
              </div>
            </div>
            <div class="form-group row"> 
              <!-- CHECKBOX PARA PORCENTAJE -->
              <div class="col-xs-6">
                <label>Usar porcentaje</label>
                <div class="form-group">
                  <input type="checkbox" class="minimal porcentaje" checked>
                  Utilizar procentaje </div>
              </div>
              <!-- ENTRADA PARA PORCENTAJE -->
              <div class="col-xs-6">
                <div class="input-group">
                  <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>
                  <span class="input-group-addon"><i class="fa fa-percent"></i></span> </div>
              </div>
            </div>
            
            <!-- ENTRADA PARA SUBIR FOTO -->
            <div class="form-group">
              <div class="panel">SUBIR IMAGEN</div>
              <input type="file" class="nuevaImagen" name="editarImagen">
              <p class="help-block">Peso máximo de la imagen 2MB</p>
              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">
              <input type="hidden" name="imagenActual" id="imagenActual">
            </div>
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
      <?php
          $editarProducto = new ControladorProductos();
          $editarProducto -> ctrEditarProducto();
        ?>
    </div>
  </div>
</div>

<?php
  $eliminarProducto = new ControladorProductos();
  $eliminarProducto -> ctrEliminarProducto();
?>

<!--=====================================
        fin
  ======================================-->
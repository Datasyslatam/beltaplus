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
      <li class="active">Filtro por productos</li>
    </ol>
  </section>
  <section class="content">

    <div class="box">

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
        <!-- <button type="button" class="btn btn-outline-secondary mx-1">Opción 1</button>
        <button type="button" class="btn btn-outline-secondary mx-1">Opción 2</button>
        <button type="button" class="btn btn-outline-secondary mx-1">Opción 3</button> -->
      </div>

    </div>
    <hr>
      <div class="box-header with-border">
        <button class="btn btn-primary me-2" onclick="mostrarTabla('tabla-filtro-tallas')"> Mostrar Filtro por Tallas </button>
        <button class="btn btn-success" onclick="mostrarTabla('tabla-filtro-precios')"> Mostrar Precios</button>
      </div>
      <div class="box-body" id="tabla-filtro-tallas">
        <table class="table table-bordered table-striped dt-responsive tablaTallas" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <!-- <th>Código</th> -->
              <th>Color</th>
              <th>TALLA 12</th>
              <th>TALLA 14</th>
              <th>TALLA 16</th>
              <th>TALLA 18</th>
              <th>TALLA 20</th>
              <th>TALLA 22</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>

        </table>
        <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
      </div>

      <div class="box-body hidden" id="tabla-filtro-precios">
        <table class="table table-bordered table-striped dt-responsive tablaPrecios" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Código</th>
              <th>Descripcion</th>
              <th>Talla</th>
              <th>Color</th>
              <th>Categoria</th>
              <th>Cantidad</th>
              <!-- <th>H Salida Cantidad</th> -->
              <!-- <th>Inventario Final</th> -->
              <th>Precio Mayorista</th>
              <th>Precion Unitario</th>
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
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                <input type="text" class="form-control input-lg" name="nuevaDescripcion" placeholder="Ingresar descripción" required>
              </div>
            </div>
            <!-- ENTRADA PARA STOCK -->
            <label for="">Stock</label>
            <div class="form-group">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="number" class="form-control input-lg" name="nuevoStock" min="0" placeholder="Stock" required>
              </div>
            </div>
            
            <!-- ENTRADA PARA PRECIO COMPRA -->
            <div class="form-group row">
              <div class="col-xs-6">
                <label for="">Precio de compra</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                  <input type="number" class="form-control input-lg" id="nuevoPrecioCompra" name="nuevoPrecioCompra" step="any" min="0" required>
                </div>
              </div>
              <div class="col-xs-6"> 
                <!-- ENTRADA PARA PRECIO VENTA -->
                <label for="">Precio de venta</label>
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
                <label for="">Precio de compra</label>
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                  <input type="number" class="form-control input-lg" id="editarPrecioCompra" name="editarPrecioCompra" step="any" min="0" required>
                </div>
              </div>
              <div class="col-xs-6"> 
                <!-- ENTRADA PARA PRECIO VENTA -->
                <label for="">Precio de venta</label>
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
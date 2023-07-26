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
      
      Administrar subcategorías
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar subcategorías</li>
    
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarSubCategoria">
          Agregar subcategoría
        </button>

      </div>
      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         <tr>
           <th style="width:10px">#</th>
           <th>Subcategoria</th>
           <th>Acciones</th>
         </tr> 
        </thead>

        <tbody>
        <?php
          $item = null;
          $valor = null;
          $subcategorias = ControladorSubCategorias::ctrMostrarSubCategorias($item, $valor);
          foreach ($subcategorias as $key => $value) {
           
            echo ' <tr>
                    <td>'.($key+1).'</td>
                    <td class="text-uppercase">'.$value["nombre"].'</td>
                    <td>
                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarCategoria" idSubCategoria="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarSubCategoria"><i class="fa fa-pencil"></i></button>';
                        if($_SESSION["perfil"] == "Administrador"){
                          echo '<button class="btn btn-danger btnEliminarSubCategoria" idSubCategoria="'.$value["id"].'"><i class="fa fa-times"></i></button>';
                        }
                      echo '</div>  
                    </td>
                  </tr>';
          }
        ?>
        </tbody>
       </table>
      </div>
    </div>
  </section>
</div>

<!--=====================================
MODAL AGREGAR SUBCATEGORÍA
======================================-->
<div id="modalAgregarSubCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar subcategoría</h4>
        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            <!-- <label for="">Nombre de la subcategoría</label>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="nuevaSubCategoria" placeholder="Ingresar subcategoría" required>
              </div>
            </div> -->

              <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
              <label for="">Categoría</label>
              <div class="form-group">
                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-th"></i></span>
                  
                  <select class="form-control input-lg" id="nuevaCategoria" name="nuevaCategoria" required>
                    <option value="<?php echo '.$value["id"].' ?> ">Selecionar categoría</option>
                    
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
                  <!-- <select class="form-control input-lg" id="nuevaSubCategoria" name="nuevaSubCategoria" required>
                    <option value="">Seleccionar Subcategoría</option> -->
                    <input type="text" class="form-control input-lg text-uppercase" name="nuevaSubCategoria" placeholder="Ingresar subcategoría" required>
                  <!-- </select> -->
                </div>
              </div>
  
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar subcategoría</button>
        </div>
        <?php
          $crearSubCategoria = new ControladorSubCategorias();
          $crearSubCategoria -> ctrCrearSubCategoria();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR SUBCATEGORÍA
======================================-->
<div id="modalEditarSubCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar subcategoría</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de la subcategoría</label>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="editarSubCategoria" id="editarSubCategoria" required>
                 <input type="hidden"  name="idSubCategoria" id="idSubCategoria" required>
              
                </div>

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
      <?php
          $editarSubCategoria = new ControladorSubCategorias();
          $editarSubCategoria -> ctrEditarSubCategoria();
        ?> 
      </form>
    </div>
  </div>
</div>
<?php
  $borrarSubCategoria = new ControladorSubCategorias();
  $borrarSubCategoria -> ctrBorrarSubCategoria();
?>

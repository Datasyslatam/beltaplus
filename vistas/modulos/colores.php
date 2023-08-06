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
      
      Administrar colores de prendas
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar colores de prendas</li>
    
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarColor">
          Agregar color
        </button>

      </div>
      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         <tr>
           <th style="width:10px">#</th>
           <th>Color</th>
           <th>Acciones</th>
         </tr> 
        </thead>

        <tbody>
        <?php
          $item = null;
          $valor = null;
          $colores = ControladorColores::ctrMostrarColores($item, $valor);
          foreach ($colores as $key => $value) {
           
            echo ' <tr>
                    <td>'.($key+1).'</td>
                    <td class="text-uppercase">'.$value["color"].'</td>
                    <td>
                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarColor" idColor="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarColor"><i class="fa fa-pencil"></i></button>';
                        if($_SESSION["perfil"] == "Administrador"){
                          echo '<button class="btn btn-danger btnEliminarColor" idColor="'.$value["id"].'"><i class="fa fa-times"></i></button>';
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
MODAL AGREGAR COLOR
======================================-->
<div id="modalAgregarColor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar color</h4>
        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE COLOR-->
            <label for="">Nombre del color</label>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="nuevoColor" placeholder="Ingresar nombre del color" required>
              </div>
            </div>
  
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar color</button>
        </div>
        <?php
          $crearColor = new ControladorColores();
          $crearColor -> ctrCrearColor();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR COLOR
======================================-->
<div id="modalEditarColor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar color</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre del color</label>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="editarColor" id="editarColor" required>
                 <input type="hidden"  name="idColor" id="idColor" required>
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
          $editarColor = new ControladorColores();
          $editarColor -> ctrEditarColor();
        ?> 
      </form>
    </div>
  </div>
</div>
<?php
  $borrarColor = new ControladorColores();
  $borrarColor -> ctrBorrarColor();
?>

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
      
      Administrar tallas de prendas
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar tallas</li>
    
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarTalla">
          Agregar talla
        </button>

      </div>
      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         <tr>
           <th style="width:10px">#</th>
           <th>Talla</th>
           <th>Acciones</th>
         </tr> 
        </thead>

        <tbody>
        <?php
          $item = null;
          $valor = null;
          $tallas = ControladorTallas::ctrMostrarTallas($item, $valor);
          foreach ($tallas as $key => $value) {
           
            echo ' <tr>
                    <td>'.($key+1).'</td>
                    <td class="text-uppercase">'.$value["talla"].'</td>
                    <td>
                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarTalla" idTalla="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarTalla"><i class="fa fa-pencil"></i></button>';
                        if($_SESSION["perfil"] == "Administrador"){
                          echo '<button class="btn btn-danger btnEliminarTalla" idTalla="'.$value["id"].'"><i class="fa fa-times"></i></button>';
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
MODAL AGREGAR TALLA PRENDA
======================================-->
<div id="modalAgregarTalla" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar talla</h4>
        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE COLOR-->
            <label for="">Nombre de la talla</label>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="nuevaTalla" placeholder="Ingresar nombre de la talla" required>
              </div>
            </div>
  
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar talla</button>
        </div>
        <?php
          $crearTalla = new ControladorTallas();
          $crearTalla -> ctrCrearTalla();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR TALLA
======================================-->
<div id="modalEditarTalla" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar talla</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de la talla</label>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg text-uppercase" name="editarTalla" id="editarTalla" required>
                 <input type="hidden"  name="idTalla" id="idTalla" required>
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
          $editarTalla = new ControladorTallas();
          $editarTalla -> ctrEditarTalla();
        ?> 
      </form>
    </div>
  </div>
</div>
<?php
  $borrarTalla = new ControladorTallas();
  $borrarTalla -> ctrBorrarTalla();
?>

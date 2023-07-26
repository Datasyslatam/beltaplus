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
      
      Administrar motivos
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar motivos</li>
    
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMotivo">
          
          Agregar motivo
        </button>
      </div>
      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Motivo</th>
           <th>Acciones</th>
         </tr> 
        </thead>
        <tbody>
        <?php
          $item = null;
          $valor = null;
          $motivos = ControladorMotivos::ctrMostrarMotivos($item, $valor);
          foreach ($motivos as $key => $value) {
           
            echo ' <tr>
                    <td>'.($key+1).'</td>
                    <td class="text-uppercase">'.$value["motivo"].'</td>
                    <td>
                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarMotivo" idMotivo="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarMotivo"><i class="fa fa-pencil"></i></button>';
                        if($_SESSION["perfil"] == "Administrador"){
                          echo '<button class="btn btn-danger btnEliminarMotivo" idMotivo="'.$value["id"].'"><i class="fa fa-times"></i></button>';
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
MODAL AGREGAR CATEGORÍA
======================================-->
<div id="modalAgregarMotivo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar motivo</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de motivo</label>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg" name="nuevaMotivo" placeholder="Ingresar motivo" required>
              </div>
            </div>
  
          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar motivo</button>
        </div>
        <?php
          $crearMotivo = new ControladorMotivos();
          $crearMotivo -> ctrCrearMotivo();
        ?>
      </form>
    </div>
  </div>
</div>
<!--=====================================
MODAL EDITAR CATEGORÍA
======================================-->
<div id="modalEditarMotivo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar motivo</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <label for="">Nombre de motivo</label>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <input type="text" class="form-control input-lg" name="editarMotivo" id="editarMotivo" required>
                 <input type="hidden"  name="idMotivo" id="idMotivo" required>
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
          $editarMotivo = new ControladorMotivos();
          $editarMotivo -> ctrEditarMotivo();
        ?> 
      </form>
    </div>
  </div>
</div>
<?php
  $borrarMotivo = new ControladorMotivos();
  $borrarMotivo -> ctrBorrarMotivo();
?>

<div id="back"></div>

<div class="login-box">

      <div class="login-box-body">

        <div class="login-logo">
          <img src="vistas/img/plantilla/logo.png" style = "width: 250px; height: 250px; margin-left:30px" class="img-responsive">
        </div>

        <p class="login-box-msg">Ingreso al sistema</p>

        <form method="post">

          <div class="form-group has-feedback">

            <input type="text" class="form-control lockscreen-name" placeholder="Usuario" name="ingUsuario" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>

          </div>

          <div class="form-group has-feedback">

            <input type="password" class="form-control lockscreen-name" placeholder="Contraseña" name="ingPassword" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          
          </div>

          <div class="row">
          
            <div class="col-xs-4 centraboton">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>          
            </div>

          </div>

          <?php

            $login = new ControladorUsuarios();
            $login -> ctrIngresoUsuario();
            
          ?>

        </form>

      </div>

</div>

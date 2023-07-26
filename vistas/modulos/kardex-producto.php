<div class="content-wrapper">
  <section class="content-header">
    
    <h1>
      
      Ver detalles del producto
    
    </h1>
    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">detalles del producto </li>
    
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <!--=====================================
      EL FORMULARIO
      ======================================-->
      
      <div class="col-lg-12 col-xs-12">
        
        <div class="box box-success">
          
          <div class="box-header with-border"></div>
            <div class="box-body">
            <div class="box-header with-border">
        <a href="movimientos"><button type="button" class="btn btn-primary"> Volver </button></a>
      </div>
  				 <?php
                    $item = "id";
                    $valor = filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT);
                    $producto = ControladorProductos::ctrMostrarProductosUnico($item, $valor);
              
                ?>
                <div class="row">
              <div class="col-sm-4 col-sm-offset-2 text-center">
				 <img class="item-img img-responsive" src="vistas/img/plantilla/stock.png" alt=""> 					
              </div>
			  
              <div class="col-sm-4 text-left">
                <div class="row margin-btm-20">
                    <div class="col-md-12">
                      <span class="item-title"> <?php echo $producto["descripcion"]; ?></span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                      <span class="item-number"><?php echo $producto["codigo"]; ?></span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                    </div>
                    <div class="col-sm-12">
                      <span class="current-stock">Stock disponible</span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                      <span class="item-quantity"><?php echo $producto["stock"]; ?></span>
                    </div>
					<div class="col-sm-12">
                      <span class="current-stock"> Precio venta  </span>
                    </div>
					<div class="col-sm-12">
                      <span class="item-price">$ <?php echo number_format($producto["precio_venta"],2); ?></span>
                    </div>
					
                    <div class="col-sm-12 margin-btm-10">
					</div>

                    <div class="col-sm-12 margin-btm-10">
                    </div>
                    
                   
                                    </div>
              </div>
            </div>
 
          </div>
                
        </div>
            
      </div>

   
      <!--=====================================
      LA TABLA DE MOVIMIENTOS PRODUCTOS
      ======================================-->
      <div class="col-lg-12 hidden-md hidden-sm hidden-xs">
        
        <div class="box box-warning">
          <div class="box-header with-border"></div>
          <div class="box-body">
          
          <div class="col-xs-12 pull-right">
                    
     <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
        <thead>
         <tr>
           <th style="width:10px">#</th>
           <th>Fecha</th>
           <th>Movimiento</th>
           <th>Cantidad</th>
         </tr> 
        </thead>
        <tbody>
        <?php
          $item = "id_producto";
          $valor = "".filter_var($_GET["idProducto"], FILTER_SANITIZE_NUMBER_INT)."";
          $movi = ControladorMovimientos::ctrMostrarkardex($item, $valor);
          foreach ($movi as $key => $value) {
           
            echo ' <tr>
                    <td>'.($key+1).'</td>
                    <td class="text-uppercase">'.$value["fecha"].'</td>
                    <td class="text-uppercase">'.$value["nota"].'</td>
                    <td class="text-uppercase">'.$value["cantidad"].'</td>
                  </tr>';
          }
        ?>
        </tbody>
       </table>
                  </div>
          
          
          </div>
        </div>
      </div>
      
      
    </div> 
          
          
          
  </section>
</div>

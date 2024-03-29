<?php
if ($_SESSION["perfil"] == "Especial") {
  echo '<script>
    window.location = "inicio";
  </script>';
  return;
}
$xml = ControladorVentas::ctrDescargarXML();
if ($xml) {
  rename($_GET["xml"] . ".xml", "xml/" . $_GET["xml"] . ".xml");
  echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/' . $_GET["xml"] . '.xml" href="ventas">Se ha creado correctamente el archivo XML <span class="fa fa-times pull-right"></span></a>';
}
?>
<div class="content-wrapper">
  <section class="content-header">

    <h1>

      Administrar ventas

    </h1>
    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar ventas</li>

    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">

        <a href="crear-venta">
          <button class="btn btn-primary">

            Agregar venta
          </button>
        </a>
        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

          <span>
            <i class="fa fa-calendar"></i>
            <?php
            if (isset($_GET["fechaInicial"])) {
              echo $_GET["fechaInicial"] . " - " . $_GET["fechaFinal"];
            } else {

              echo 'Rango de fecha';
            }
            ?>
          </span>
          <i class="fa fa-caret-down"></i>
        </button>
      </div>
      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">

          <thead>

            <tr>

              <th style="width:10px">#</th>
              <th>Código factura</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Forma de pago</th>
              <th>Neto</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET["fechaInicial"])) {
              $fechaInicial = $_GET["fechaInicial"];
              $fechaFinal = $_GET["fechaFinal"];
            } else {
              $fechaInicial = null;
              $fechaFinal = null;
            }
            $respuesta = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);
            foreach ($respuesta as $key => $value) {

              echo '<tr>
                  <td>' . ($key + 1) . '</td>
                  <td>' . $value["codigo"] . '</td>';
              $itemCliente = "id";
              $valorCliente = $value["id_cliente"];
              $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
              echo '<td>' . $respuestaCliente["nombre"] . '</td>';
              $itemUsuario = "id";
              $valorUsuario = $value["id_vendedor"];
              $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);
              /*<a class="btn btn-success" href="index.php?ruta=ventas&xml='.$value["codigo"].'">xml</a>*/
              echo '<td>' . $respuestaUsuario["nombre"] . '</td>
                  <td>' . $value["metodo_pago"] . '</td>
                  <td>$ ' . number_format($value["neto"], 2) . '</td>
                  <td>$ ' . number_format($value["total"], 2) . '</td>
                  <td>' . $value["fecha_venta"] . '</td>
                  <td>
                    <div class="btn-group">
                      
                      <button title="Factura Carta" class="btn btn-info btnImprimirFacturaCarta" codigoVenta="' . $value["codigo"] . '">
                        <i class="fa fa-print"></i>
                      </button>  
                      <button title="Factura Ticket" class="btn btn-info btnImprimirFactura" codigoVenta="' . $value["codigo"] . '">
                        <i class="fa fa-print"></i>
                      </button>';
              if ($_SESSION["perfil"] == "Administrador") {
                echo '<button class="btn btn-warning btnEditarVenta" idVenta="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>';
                      // <button class="btn btn-danger btnEliminarVenta" idVenta="' . $value["id"] . '"><i class="fa fa-times"></i></button>';
                if(ControladorTransito::ctrVerificarVentaEnProceso($value['id'])){
                  echo '<button class="btn btn-success btnPagarVentaProcess" idVenta="' . $value["id"] . '"><i class="fa fa-check"> Confirmar Pago</i></button>
                    <button class="btn btn-danger btnEliminarVentaProcess" idVenta="' . $value["id"] . '"><i class="fa fa-times"> Devolucion</i></button>';
                }
              }
              echo '</div>
                  </td>
                </tr>';
            }
            ?>

          </tbody>
        </table>
        <?php
        $eliminarTransito = new ControladorTransito();
        $eliminarTransito->ctrEliminarProductosTransito();
        $eliminarVenta = new ControladorVentas();
        $eliminarVenta->ctrEliminarVenta();
        $confirmarVenta = new ControladorVentasTemp();
        $confirmarVenta->ctrEliminarVenta(true, 'ventas_proceso');
        ?>

      </div>
    </div>
  </section>
</div>
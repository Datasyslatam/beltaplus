<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura
{

	public $codigo;

	public function traerImpresionFactura()
	{

		//TRAEMOS LA INFORMACIÓN DE LA VENTA

		$itemVenta = "codigo";
		$valorVenta = $this->codigo;

		$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

		$fecha = substr($respuestaVenta["fecha"], 0, -8);
		$productos = json_decode($respuestaVenta["productos"], true);
		$neto = number_format($respuestaVenta["neto"]);
		$impuesto = number_format($respuestaVenta["impuesto"]);
		$transportadora = $respuestaVenta["transportadora"];

		// echo '<pre>';
		// var_dump($productos);
		// foreach ($productos as $key => $item) {

		// 	$itemProducto = "descripcion";
		// 	// $valorProducto = $item["codigo"] . " " .$item["descripcion"];
		// 	$valorProducto = $item["descripcion"];
		// 	$orden = null;

		// 	$cantidad = $item["cantidad"];
		// 	$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);
		// 	if (is_string($respuestaProducto)) {
		// 		$valorUnitario = "Na";
		// 		$valorMayor = "Na";
		// 	} else {
		// 		if ($cantidad >= 6) {
		// 			$valorMayor = "$" . number_format($respuestaProducto["precio_compra"]); // valor por mayor identificado como "precio de compra"
		// 			$precioFinal = number_format($valorMayor + $respuestaVenta["impuesto"]);

		// 		} else {
		// 			$valorUnitario = number_format($respuestaProducto["precio_venta"]);
		// 			$valorMayor = "No aplica";
		// 			$precioTotal = number_format($respuestaProducto["precio_venta"] * $cantidad);
		// 			$precioFinal = $respuestaVenta["neto"] + $respuestaVenta["impuesto"];

		// 		}
		// 	}
		// }
		// echo '</pre>';

		//TRAEMOS LA INFORMACIÓN DEL CLIENTE

		$itemCliente = "id";
		$valorCliente = $respuestaVenta["id_cliente"];

		$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

		//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

		$itemVendedor = "id";
		$valorVendedor = $respuestaVenta["id_vendedor"];

		$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

		//REQUERIMOS LA CLASE TCPDF

		require_once('tcpdf_include.php');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->startPageGroup();

		$pdf->AddPage();

		// ---------------------------------------------------------

		$bloque1 = <<<EOF

	<table border="0">
		
		<tr>
			
			<td style="width:150px"><img src="images/logoBeltaFull.png"></td>

			<td style="background-color:white; width:140px">
				
				<div style="font-size:8.5px; text-align:right; line-height:14px;">
					
					<br>
					<H3 style="font: size 14px; color: #27ae60;">PEDIDOS:</H3>

					<br>
					NIT: 52163724-2

				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					<H3>312 808 4806</H3>
					
					<br>
					beltaplusize@gmail.com

				</div>
				
			</td>

			<td style="background-color:white; width:110px; text-align:center; color: red; font-weight: bold;"><br><br>PEDIDO No.<br>$valorVenta</td>

		</tr>

	</table>

EOF;

		$pdf->writeHTML($bloque1, false, false, false, false, '');

		// ---------------------------------------------------------

		$bloque2 = <<<EOF

	<table style="border: white 1px none;  background-color:white">
		
		<tr>
			
			<td style="border: white 1px none; width:540px"><img src="images/back.jpg"></td>
		
		</tr>

	</table>

	<table style="border: white 1px none; font-size:10px; padding:2px 4px;">
	
		<tr>
		
			<td style="border: white 1px none; background-color:white; width:390px">

				Cliente: $respuestaCliente[nombre]

			</td>

			<td style="border: white 1px none; background-color:white; width:150px; text-align:right">
			
				Fecha: $fecha

			</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Cédula / Nit: $respuestaCliente[documento]</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Celular: $respuestaCliente[telefono]</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Dirección de envio: $respuestaCliente[direccion]</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Ciudad: $respuestaCliente[ciudad]</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Transporte: $transportadora</td>

		</tr>

		<tr>
		
			<td style="border: white 1px none; background-color:white; width:540px">Vendedor: $respuestaVendedor[nombre]</td>

		</tr>

		<tr>
		
		<td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>

		</tr>

	</table>

EOF;

		$pdf->writeHTML($bloque2, false, false, false, false, '');

		// ---------------------------------------------------------

		$bloque3 = <<<EOF

	<table style="font-size:10px; padding:3px 6px;">

		<tr>
		
		<td style="border: 1px solid #666; background-color:white; width: 188px; text-align:center">Producto</td>
		<td style="border: 1px solid #666; background-color:white; width:  85px; text-align:center">Cantidad</td>
		<td style="border: 1px solid #666; background-color:white; width:  85px; text-align:center">Valor Unit.</td>
		<td style="border: 1px solid #666; background-color:white; width:  85px; text-align:center">Valor por mayor.</td>
		<td style="border: 1px solid #666; background-color:white; width: 100px; text-align:center">Valor Total</td>
	</tr>

	</table>

EOF;

		$pdf->writeHTML($bloque3, false, false, false, false, '');

		// ---------------------------------------------------------



		foreach ($productos as $key => $item) {

			$itemProducto = "codigo";
			// $valorProducto = $item["codigo"] . " " .$item["descripcion"];
			$valorProducto = $item["codigo"];
			$orden = null;

			$cantidad = $item["cantidad"];
			$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);
			if (is_string($respuestaProducto)) {
				$valorUnitario = "Na";
				$valorMayor = "Na";
			} else {
				if ($cantidad >= 6) {
					$valorMayor = "$" . number_format($respuestaProducto["precio_compra"]); // valor por mayor identificado como "precio de compra"
					$precioFinal = number_format($valorMayor + $respuestaVenta["impuesto"]);
					

				} else {
					$valorUnitario = number_format($respuestaProducto["precio_venta"]);
					$valorMayor = "No aplica";
					$precioTotal = number_format($respuestaProducto["precio_venta"] * $cantidad);
					$precioFinal = $respuestaVenta["neto"] + $respuestaVenta["impuesto"];

				}
			}


			$bloque4 = <<<EOF

		<table style="font-size:10px; padding:3px 6px;">
			<tr>
				
				<td style="border: 1px solid #666; color:#333; background-color:white; width: 188px; text-align:center">
					$item[descripcion]
				</td>

				<td style="border: 1px solid #666; color:#333; background-color:white; width: 85px; text-align:center">
					$cantidad
				</td>

				<td style="border: 1px solid #666; color:#333; background-color:white; width: 85px; text-align:center">$ 
					$valorUnitario
				</td>

				<td style="border: 1px solid #666; color:#333; background-color:white; width: 85px; text-align:center"> 
					$valorMayor
				</td>

				<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$ 
					$precioTotal
				</td>


			</tr>

		</table>
	EOF;

			$pdf->writeHTML($bloque4, false, false, false, false, '');

		}

		// ---------------------------------------------------------

		$bloque5 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"></td>

		</tr>
		
		<tr>
		
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666;  background-color:white; width:100px; text-align:center">
				Neto:
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $neto
			</td>

		</tr>

		<tr>

			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
				Impuesto:
			</td>
		
			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $impuesto
			</td>

		</tr>

		<tr>
		
			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
				Total:
			</td>
			
			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $precioFinal
			</td>

		</tr>


	</table>

EOF;

		$pdf->writeHTML($bloque5, false, false, false, false, '');

		// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

		//$pdf->Output('factura.pdf', 'D');
		$pdf->Output('factura.pdf');

	}

}

$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFactura();

?>
<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

require_once "../../../controladores/colores.controlador.php";
require_once "../../../modelos/colores.modelo.php";

require_once "../../../controladores/tallas.controlador.php";
require_once "../../../modelos/tallas.modelo.php";

class imprimirFactura
{

	public $codigo;

	public function traerImpresionFactura()
	{

		//TRAEMOS LA INFORMACIÓN DE LA VENTA

		$itemVenta = "codigo";
		$valorVenta = $this->codigo;

		$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

		$fecha = date($respuestaVenta["fecha_venta"]);
		$productos = json_decode($respuestaVenta["productos"], true);
		// $neto = number_format($respuestaVenta["neto"]);
		$impuesto = number_format($respuestaVenta["impuesto"]);
		$transportadora = $respuestaVenta["transportadora"];

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
		$tableStyle = 'width: 100%; border-collapse: collapse;';
		$tdStyle = 'background-color: white; vertical-align: top;';

		$bloque1 = <<<EOF
			<table border="0" style="$tableStyle">
				<tr>
					<td style="width: 150px; $tdStyle">
						{$pdf->Image('images/logoBeltaFull.png', $pdf->GetX() + 10, $pdf->GetY() + 10, 50, "")}
					</td>
					<td style="width: 140px; $tdStyle">
						<div style="font-size: 8.5px; text-align: right; line-height: 10px;">
							<h3 style="font-size: 14px; color: #27ae60; margin: 0;">PEDIDOS:</h3>
							<h6>NIT: 52163724-2</h6>	
						</div>
					</td>
					<td style="width: 140px; $tdStyle">
						<div style="font-size: 8.5px; text-align: right; line-height: 10px;">
							<h3 style="margin: 0;"><img src="images/fa-whatsapp1.jpg" width="15px" height="15px"> 312 808 4806</h3>
							<h6>beltaplusize@gmail.com</h6>	
						</div>
					</td>
					<td style="width: 110px; text-align: right; color: red; font-weight: bold; $tdStyle;">
						<div style="text-align: right; line-height: 15px; margin-top: 50px;">
							<br>
							<br>
							PEDIDO No.<br>$valorVenta
						</div>
					</td>
				</tr>
			</table>
			EOF;


		$pdf->writeHTML($bloque1, false, false, false, false, '');

		// ---------------------------------------------------------

		$bloque2 = <<<EOF

			<table style="border: white 1px none;  background-color:white">
				<tr>				
					<td style="border: white 1px none; width:540px"><img src="images/back001.jpg"></td>	
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
					<td style="border: 1px solid #666; background-color:white; width: 152px; text-align:center">Producto</td>
					<td style="border: 1px solid #666; background-color:white; width:  50px; text-align:center">Talla</td>
					<td style="border: 1px solid #666; background-color:white; width:  56px; text-align:center">Color</td>
					<td style="border: 1px solid #666; background-color:white; width:  60px; text-align:center">Cantidad</td>
					<td style="border: 1px solid #666; background-color:white; width:  60px; text-align:center">Valor Unit.</td>
					<td style="border: 1px solid #666; background-color:white; width:  60px; text-align:center">Valor por mayor.</td>
					<td style="border: 1px solid #666; background-color:white; width: 102px; text-align:center">Valor Total</td>
				</tr>
			</table>

			EOF;

		$pdf->writeHTML($bloque3, false, false, false, false, '');

		// ---------------------------------------------------------


		$valor_acumulado = 0;
		$cantidad_acumulada = 0;
		foreach($productos as $key => $item){
			$cantidad = $item["cantidad"];
			$cantidad_acumulada += $cantidad;
		}
		foreach ($productos as $key => $item) {

			$itemProducto = "id";
			$valorProducto = $item["id"];
			$orden = null;

			$cantidad = $item["cantidad"];
			$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);


			$respuestaTalla = ControladorTallas::ctrMostrarTallas($itemProducto, $respuestaProducto["id_talla"]);
			$respuestaColor = ControladorColores::ctrMostrarColores($itemProducto, $respuestaProducto["id_color"]);


			$tallaString = $respuestaTalla["talla"] ?? "NaN";
			$color = $respuestaColor["color"] ?? "NaN";
			if ($tallaString !== "NaN") {
				$talla = preg_replace("/[^0-9]/", "", $tallaString);
			} else {
				$talla = $tallaString;
			}


			if ($cantidad_acumulada >= 6) {

				$valorMayor = "$" . number_format($respuestaProducto["precio_compra"]); // valor por mayor identificado como "precio de compra"
				$precioTotal = number_format($respuestaProducto["precio_compra"] * $cantidad);
				$valor_acumulado += $respuestaProducto["precio_compra"] * $cantidad;
				$valorUnitario = "No aplica";



			} else {
				$valorUnitario = "$" . number_format($respuestaProducto["precio_venta"]);

				$valorMayor = "No aplica";
				$precioTotal = number_format($respuestaProducto["precio_venta"] * $cantidad);
				$valor_acumulado += $respuestaProducto["precio_venta"] * $cantidad;


			}
			$neto = number_format($valor_acumulado);
			$precioFinal = number_format($valor_acumulado + $respuestaVenta["impuesto"]);


			$bloque4 = <<<EOF

				<table style="font-size:10px; padding:2px 4px;">
					<tr>					
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 152px; text-align:center; font-size:8px; padding:2px 4px;">
							$item[descripcion]
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 50px; text-align:center; font-size:8px; padding:2px 4px;">
							$talla
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 56px; text-align:center; font-size:8px; padding:2px 4px;">
							$color
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 60px; text-align:center; font-size:8px; padding:2px 4px;">
							$cantidad
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 60px; text-align:center; font-size:8px; padding:2px 4px;">
							$valorUnitario
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 60px; text-align:center; font-size:8px; padding:2px 4px;">
							$valorMayor
						</td>
						<td style="border: 1px solid #666; color:#333; background-color:white; width: 102px; text-align:right; font-size:10px; padding:2px 4px;">$ 
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
					<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:right; font-size:8px; padding:2px 4px;">
						$ $neto
					</td>
				</tr>
				<tr>
					<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>
					<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
						Impuesto:
					</td>			
					<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:right; font-size:8px; padding:2px 4px;">
						$ $impuesto
					</td>
				</tr>
				<tr>		
					<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>
					<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
						Total:
					</td>			
					<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:right; font-size:11px; padding:2px 4px; font-weight: bold;">
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
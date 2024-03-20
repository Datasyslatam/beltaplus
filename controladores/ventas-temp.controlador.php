<?php

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ControladorVentasTemp
{
	/*=============================================
	   MOSTRAR VENTAS
	   =============================================*/
	public static function ctrMostrarVentas($item, $valor, $tabla="ventas_temp")
	{
		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
		return $respuesta;
	}
	/*=============================================
	   CREAR VENTA
	   =============================================*/
	public static function ctrCrearVenta($tabla = 'ventas_temp')
	{
		if (isset($_POST["nuevaVenta"])) {
			/*=============================================
					 ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
					 =============================================*/
			if ($_POST["listaProductos"] == "") {
				echo '<script>
				swal({
					  type: "error",
					  title: "La venta no se ha ejecuta si no hay productos",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
				return;
			}
			$listaProductos = json_decode($_POST["listaProductos"], true);
			$totalProductosComprados = array();
			foreach ($listaProductos as $key => $value) {
				array_push($totalProductosComprados, $value["cantidad"]);
				$tablaProductos = "productos";
				$item = "id";
				$valor = $value["id"];
				$orden = "id";
				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $value["cantidad"] + $traerProducto["ventas"];
				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				// $item1b = "stock";
				// $valor1b = $value["stock"];
				// $nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

				/*Ingresar al kardex*/
				$tablaHistorial = "historial";
				$cant = $value["cantidad"];
				$idp = $value["id"];
				$rhistorial = ModeloMovimientos::mdlIngresoMovimientoVenta($tablaHistorial, $cant, $idp);
				/*FIn:Ingresar al kardex*/
			}
			$tablaClientes = "clientes";
			$item = "id";
			$valor = $_POST["seleccionarCliente"];
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);
			$item1a = "compras";

			$valor1a = array_sum($totalProductosComprados) + $traerCliente["compras"];
			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valor);
			$item1b = "ultima_compra";
			date_default_timezone_set('America/Bogota');
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha . ' ' . $hora;
			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valor);

			/*=============================================
					 GUARDAR LA VENTA
			=============================================*/
			// $tabla = "ventas_temp";
			$datos = array(
				"id_vendedor" => $_POST["idVendedor"],
				"id_cliente" => $_POST["seleccionarCliente"],
				"codigo" => $_POST["nuevaVenta"],
				"fecha_venta" => $_POST["nuevaFechaVenta"],		// Fecha de Venta nvo campo
				"productos" => $_POST["listaProductos"],
				"impuesto" => $_POST["nuevoPrecioImpuesto"],
				"neto" => $_POST["nuevoPrecioNeto"],
				"total" => $_POST["totalVenta"],
				"metodo_pago" => $_POST["listaMetodoPago"],
				"transportadora" => $_POST["nuevaTransporta"]
			);


			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			if ($respuesta == "ok") {
				if ($tabla === 'ventas_temp') {
					echo '<script>
					localStorage.removeItem("rango");
					swal({
						  type: "success",
						  title: "El carrito ha sido actualizado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "ventas-temp";
									}
								})
					</script>';
				} else {
					echo '<script>
					localStorage.removeItem("rango");
					swal({
						  type: "success",
						  title: "El carrito se ha comprado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "ventas";
									}
								})
					</script>';
				}
			}
		}
	}

	/*=============================================
	   EDITAR VENTA
	=============================================*/
	public static function ctrEditarVenta($tabla = "ventas_temp")
	{
		if (isset($_POST["editarVenta"])) {
			/*=============================================
					 FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$item = "codigo";
			$valor = $_POST["editarVenta"];
			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			/*=============================================
					 REVISAR SI VIENE PRODUCTOS EDITADOS
					 =============================================*/
			if ($_POST["listaProductos"] == "") {
				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;
			} else {
				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}
			if ($cambioProducto) {
				$productos = json_decode($traerVenta["productos"], true);
				$totalProductosComprados = array();
				foreach ($productos as $key => $value) {
					array_push($totalProductosComprados, $value["cantidad"]);

					$tablaProductos = "productos";
					$item = "id";
					$valor = $value["id"];
					$orden = "id";
					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);
					$item1a = "ventas_temp";
					// $valor1a = $traerProducto["ventas_temp"] - $value["cantidad"];
					// $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);
					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];
					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
				}
				$tablaClientes = "clientes";
				$itemCliente = "id";
				$valorCliente = $_POST["seleccionarCliente"];
				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);
				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);
				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);
				/*=============================================
					ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/
				$listaProductos_2 = json_decode($listaProductos, true);
				$totalProductosComprados_2 = array();
				foreach ($listaProductos_2 as $key => $value) {
					array_push($totalProductosComprados_2, $value["cantidad"]);

					$tablaProductos_2 = "productos";
					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";
					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2, $orden);
					$item1a_2 = "ventas_temp";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["ventas_temp"];
					$nuevasVentas_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);
					$item1b_2 = "stock";
					$valor1b_2 = $traerProducto_2["stock"] - $value["cantidad"];
					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);
				}
				$tablaClientes_2 = "clientes";
				$item_2 = "id";
				$valor_2 = $_POST["seleccionarCliente"];
				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);
				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];
				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);
				$item1b_2 = "ultima_compra";
				date_default_timezone_set('America/Bogota');
				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha . ' ' . $hora;
				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);
			}
			/*=============================================
				GUARDAR CAMBIOS DE LA COMPRA PENDIENTE
			=============================================*/
			$datos = array(
				"id_vendedor" => $_POST["idVendedor"],
				"id_cliente" => $_POST["seleccionarCliente"],
				"codigo" => $_POST["editarVenta"],
				"fecha_venta" => $_POST["nuevaFechaVenta"],			// Nva fecha de Venta
				"productos" => $listaProductos,
				"impuesto" => $_POST["nuevoPrecioImpuesto"],
				"neto" => $_POST["nuevoPrecioNeto"],
				"total" => $_POST["totalVenta"],
				"metodo_pago" => $_POST["listaMetodoPago"]
			);

			$respuesta = ModeloVentas::mdlEditarVenta($tabla, $datos);
			if ($respuesta == "ok") {
				echo '<script>
				localStorage.removeItem("rango");
				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {
								window.location = "ventas-temp";
								}
							})
				</script>';
			}
		}
	}
	/*=============================================
	   ELIMINAR VENTA
	=============================================*/
	public static function ctrEliminarVenta($t = true, $tabla = "ventas_temp")
	{
		if (isset($_GET["idVenta"])) {
			$item = "id";
			$valor = $_GET["idVenta"];
			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			/*=============================================
				ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/
			$tablaClientes = "clientes";
			$itemVentas = null;
			$valorVentas = null;
			$traerVentas = ModeloVentas::mdlMostrarVentas($tabla, $itemVentas, $valorVentas);
			$guardarFechas = array();
			foreach ($traerVentas as $key => $value) {

				if ($value["id_cliente"] == $traerVenta["id_cliente"]) {
					array_push($guardarFechas, $value["fecha"]);
				}
			}
			if (count($guardarFechas) > 1) {
				if ($traerVenta["fecha"] > $guardarFechas[count($guardarFechas) - 2]) {
					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas) - 2];
					$valorIdCliente = $traerVenta["id_cliente"];
					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
				} else {
					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas) - 1];
					$valorIdCliente = $traerVenta["id_cliente"];
					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
				}
			} else {
				$item = "ultima_compra";
				$valor = "9000-01-01 00:00:00";
				$valorIdCliente = $traerVenta["id_cliente"];
				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);
			}
			/*=============================================
				FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			 =============================================*/
			$productos = json_decode($traerVenta["productos"], true);
			$totalProductosComprados = array();
			foreach ($productos as $key => $value) {
				array_push($totalProductosComprados, $value["cantidad"]);

				$tablaProductos = "productos";
				$item = "id";
				$valor = $value["id"];
				$orden = "id";
				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);
				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];
				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);
				// $item1b = "stock";
				// $valor1b = $value["cantidad"] + $traerProducto["stock"];
				// $nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
			}
			$tablaClientes = "clientes";
			$itemCliente = "id";
			$valorCliente = $traerVenta["id_cliente"];
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);
			$item1a = "compras";
			$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);
			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);
			/*=============================================
			 ELIMINAR VENTA
			=============================================*/
			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);
			if ($respuesta == "ok") {
				if ($t) {
					echo '<script>
					swal({
						  type: "success",
						  title: "¡Accion Confirmada!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "ventas";
									}
								})
					</script>';
				}
			}
		}
	}
	/*=============================================
	   RANGO FECHAS
	   =============================================*/
	public static function ctrRangoFechasVentas($fechaInicial, $fechaFinal, $tabla = "ventas_temp")
	{
		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);
		return $respuesta;
	}
	/*=============================================
	   DESCARGAR EXCEL
	   =============================================*/
	public function ctrDescargarReporte($tabla = "ventas_temp")
	{
		if (isset($_GET["reporte"])) {
			if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
				$ventas = ModeloVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);
			} else {
				$item = null;
				$valor = null;
				$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			}
			/*=============================================
					 CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/
			$Name = $_GET["reporte"] . '.xls';
			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate");
			header('Content-Description: File Transfer');
			header('Last-Modified: ' . date('D, d M Y H:i:s'));
			header("Pragma: public");
			header('Content-Disposition:; filename="' . $Name . '"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 
					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");
			foreach ($ventas as $row => $item) {
				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_vendedor"]);
				echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>" . $item["codigo"] . "</td> 
			 			<td style='border:1px solid #eee;'>" . $cliente["nombre"] . "</td>
			 			<td style='border:1px solid #eee;'>" . $vendedor["nombre"] . "</td>
			 			<td style='border:1px solid #eee;'>");
				$productos = json_decode($item["productos"], true);
				foreach ($productos as $key => $valueProductos) {

					echo utf8_decode($valueProductos["cantidad"] . "<br>");
				}
				echo utf8_decode("</td><td style='border:1px solid #eee;'>");
				foreach ($productos as $key => $valueProductos) {

					echo utf8_decode($valueProductos["descripcion"] . "<br>");
				}
				echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ " . number_format($item["impuesto"], 2) . "</td>
					<td style='border:1px solid #eee;'>$ " . number_format($item["neto"], 2) . "</td>	
					<td style='border:1px solid #eee;'>$ " . number_format($item["total"], 2) . "</td>
					<td style='border:1px solid #eee;'>" . $item["metodo_pago"] . "</td>
					<td style='border:1px solid #eee;'>" . substr($item["fecha"], 0, 10) . "</td>		
		 			</tr>");
			}
			echo "</table>";
		}
	}
	/*=============================================
	   SUMA TOTAL VENTAS
	   =============================================*/
	public static function ctrSumaTotalVentas($tabla = "ventas_temp")
	{
		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);
		return $respuesta;
	}
	/*=============================================
	   DESCARGAR XML
	   =============================================*/
	public static function ctrDescargarXML($tabla = "ventas_temp")
	{
		if (isset($_GET["xml"])) {
			$item = "codigo";
			$valor = $_GET["xml"];
			$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			// PRODUCTOS
			$listaProductos = json_decode($ventas["productos"], true);
			// CLIENTE
			$tablaClientes = "clientes";
			$item = "id";
			$valor = $ventas["id_cliente"];
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);
			// VENDEDOR
			$tablaVendedor = "usuarios";
			$item = "id";
			$valor = $ventas["id_vendedor"];
			$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);
			//http://php.net/manual/es/book.xmlwriter.php
			$objetoXML = new XMLWriter();
			$objetoXML->openURI($_GET["xml"] . ".xml"); //Creación del archivo XML
			$objetoXML->setIndent(true); //recibe un valor booleano para establecer si los distintos niveles de nodos XML deben quedar indentados o no.
			$objetoXML->setIndentString("\t"); // carácter \t, que corresponde a una tabulación
			$objetoXML->startDocument('1.0', 'utf-8'); // Inicio del documento

			// $objetoXML->startElement("etiquetaPrincipal");// Inicio del nodo raíz
			// $objetoXML->writeAttribute("atributoEtiquetaPPal", "valor atributo etiqueta PPal"); // Atributo etiqueta principal
			// 	$objetoXML->startElement("etiquetaInterna");// Inicio del nodo hijo
			// 		$objetoXML->writeAttribute("atributoEtiquetaInterna", "valor atributo etiqueta Interna"); // Atributo etiqueta interna
			// 		$objetoXML->text("Texto interno");// Inicio del nodo hijo

			// 	$objetoXML->endElement(); // Final del nodo hijo

			// $objetoXML->endElement(); // Final del nodo raíz
			$objetoXML->writeRaw('<fe:Invoice xmlns:fe="http://www.dian.gov.co/contratos/facturaelectronica/v1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dian.gov.co/contratos/facturaelectronica/v1 ../xsd/DIAN_UBL.xsd urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2 ../../ubl2/common/UnqualifiedDataTypeSchemaModule-2.0.xsd urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2 ../../ubl2/common/UBL-QualifiedDatatypes-2.0.xsd">');
			$objetoXML->writeRaw('<ext:UBLExtensions>');
			foreach ($listaProductos as $key => $value) {

				$objetoXML->text($value["descripcion"] . ", ");
			}

			$objetoXML->writeRaw('</ext:UBLExtensions>');
			$objetoXML->writeRaw('</fe:Invoice>');
			$objetoXML->endDocument(); // Final del documento
			return true;
		}
	}
}

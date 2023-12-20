<?php

require_once "conexion.php";

class ModeloProductos
{

	/*=============================================
		  MOSTRAR PRODUCTOS
		  =============================================*/
	public static function mdlMostrarProductos($tabla, $item, $valor, $orden)
	{
		if ($item != null) {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id_categoria ASC");
			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
			$stmt->execute();
		
			echo $stmt->queryString; 
			$resultado = $stmt->fetch();
			var_dump($resultado);
		
			if ($resultado) {
				return $resultado;
			} else {
				return "Producto no encontrado";
			}
		} else {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden ASC");
			$stmt->execute();
			
			echo $stmt->queryString; 
			return $stmt->fetchAll();
		}
		$stmt->close();
		$stmt = null;
	}

	/*=============================================
		  FILTRAR PRODUCTOS
		  =============================================*/
	public static function mdlFiltrarProductos($valor)
	{


		$stmt = Conexion::conectar()->prepare("SELECT CONCAT(p.codigo,' ', ca.categoria,' ',sb.nombre) AS descripcion_producto,
												p.*
												FROM productos p
												INNER JOIN tallas t ON  t.id = p.id_talla
												INNER JOIN colores co ON co.id = p. id_color
												INNER JOIN categorias ca ON ca.id = p.id_categoria
												INNER JOIN subcategorias sb ON sb.id = p.id_subcategoria 
												WHERE p.id = :id ORDER BY id_categoria ASC");
		$stmt->bindParam(":id", $valor, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch();
	}

	/*=============================================
		  MOSTRAR PRODUCTOS UNICOS	
		  =============================================*/
	public static function mdlMostrarProductosUnicos($tabla, $item, $valor)
	{
		if ($item != null) {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id_categoria ASC");
			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch();
		} else {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
			$stmt->execute();
			return $stmt->fetchAll();
		}
		$stmt->close();
		$stmt = null;
	}
	/*=============================================
		  REGISTRO DE PRODUCTO
		  =============================================*/
	public static function mdlIngresarProducto($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, id_subcategoria, codigo, imagen, stock, precio_compra, precio_venta, ventas, id_color, id_talla) VALUES(:id_categoria, :id_subcategoria, UPPER(:codigo), :imagen, :stock, :precio_compra, :precio_venta, :ventas, :id_color, :id_talla)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_subcategoria", $datos["id_subcategoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		/* $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR); */
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_INT);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_INT);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_INT);
		$stmt->bindParam(":ventas", $datos["ventas"], PDO::PARAM_INT);
		$stmt->bindParam(":id_color", $datos["id_color"], PDO::PARAM_INT);
		$stmt->bindParam(":id_talla", $datos["id_talla"], PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";

		} else {

			return "error";

		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
		  EDITAR PRODUCTO
		  =============================================*/
	public static function mdlEditarProducto($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, id_subcategoria = :id_subcategoria, imagen = :imagen, stock = :stock, precio_compra = :precio_compra, precio_venta = :precio_venta WHERE codigo = :codigo");


		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_subcategoria", $datos["id_subcategoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		/* $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR); */
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";

		} else {

			return "error";

		}

		$stmt->close();
		$stmt = null;
	}

	/*=============================================
		  BORRAR PRODUCTO
		  =============================================*/

	public static function mdlEliminarProducto($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";

		} else {

			return "error";

		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
		  ACTUALIZAR PRODUCTO
		  =============================================*/

	public static function mdlActualizarProducto($tabla, $item1, $valor1, $valor)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt->bindParam(":" . $item1, $valor1, PDO::PARAM_STR);
		$stmt->bindParam(":id", $valor, PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";

		} else {

			return "error";

		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
		  MOSTRAR SUMA VENTAS
		  =============================================*/

	public static function mdlMostrarSumaVentas($tabla)
	{

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt->execute();

		return $stmt->fetch();

		$stmt->close();
		$stmt = null;
	}


}
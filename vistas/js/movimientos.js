/*=============================================
CARGAR LA TABLA DINÁMICA DE PRODUCTOS
=============================================*/

$.ajax({

	url: "ajax/datatable-movimientos.ajax.php",
	success:function(respuesta){
		
		//console.log("respuesta", respuesta);

	}

})

var perfilOculto = $("#perfilOculto").val();

$('.tablaMovimientos').DataTable( {
    "ajax": "ajax/datatable-movimientos.ajax.php?perfilOculto="+perfilOculto,
    "deferRender": true,
	"retrieve": true,
	"processing": true,
	 "language": {

			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}

	}

} );


/*=============================================
INGRESO PRODUCTO
=============================================*/
$(".tablaMovimientos tbody").on("click", "button.btnIngresoMovimiento", function(){

	var idMovimiento = $(this).attr("idMovimiento");
	var datos = new FormData();
    datos.append("idMovimiento", idMovimiento);
     $.ajax({
      url:"ajax/movimientos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
           $("#ingresoId").val(respuesta["id"]);
           $("#ingresoDescripcion").val(respuesta["descripcion"]);

      }

  })

})
/*=============================================
SALIDA PRODUCTO
=============================================*/
$(".tablaMovimientos tbody").on("click", "button.btnSalidaMovimiento", function(){

	var idMovimiento = $(this).attr("idMovimiento");
	var datos = new FormData();
    datos.append("idMovimiento", idMovimiento);
     $.ajax({
      url:"ajax/movimientos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
           $("#salidaId").val(respuesta["id"]);
           $("#salidaDescripcion").val(respuesta["descripcion"]);

      }

  })

})

	

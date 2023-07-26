/*=============================================
EDITAR MOTIVO
=============================================*/
$(".tablas").on("click", ".btnEditarMotivo", function(){

	var idMotivo = $(this).attr("idMotivo");

	var datos = new FormData();
	datos.append("idMotivo", idMotivo);

	$.ajax({
		url: "ajax/motivos.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){

     		$("#editarMotivo").val(respuesta["motivo"]);
     		$("#idMotivo").val(respuesta["id"]);

     	}

	})


})

/*=============================================
ELIMINAR MOTIVO
=============================================*/
$(".tablas").on("click", ".btnEliminarMotivo", function(){

	 var idMotivo = $(this).attr("idMotivo");

	 swal({
	 	title: '¿Está seguro de borrar el motivo?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar motivo!'
	 }).then(function(result){

	 	if(result.value){

	 		window.location = "index.php?ruta=motivos&idMotivo="+idMotivo;

	 	}

	 })

})
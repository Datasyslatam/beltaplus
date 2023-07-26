/*=============================================
EDITAR TALLA
=============================================*/
$(".tablas").on("click", ".btnEditarTalla", function(){

	var idTalla = $(this).attr("idTalla");

	var datos = new FormData();
	datos.append("idTalla", idTalla);

	$.ajax({
		url: "ajax/tallas.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){

     		$("#editarTalla").val(respuesta["talla"]);
     		$("#idTalla").val(respuesta["id"]);

     	}

	})

})

/*=============================================
ELIMINAR TALLA
=============================================*/
$(".tablas").on("click", ".btnEliminarTalla", function(){

	 var idTalla = $(this).attr("idTalla");

	 swal({
	 	title: '¿Está seguro de borrar la talla?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar talla!'
	 }).then(function(result){

	 	if(result.value){

	 		window.location = "index.php?ruta=tallas&idTalla="+idTalla;

	 	}

	 })
})
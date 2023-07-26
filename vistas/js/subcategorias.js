/*=============================================
EDITAR SUBCATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEditarSubCategoria", function(){

	var idSubCategoria = $(this).attr("idSubCategoria");

	var datos = new FormData();
	datos.append("idSubCategoria", idSubCategoria);

	$.ajax({
		url: "ajax/subcategorias.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){

     		$("#editarSubCategoria").val(respuesta["nombre"]);
     		$("#idSubCategoria").val(respuesta["id"]);

     	}

	})

})

/*=============================================
ELIMINAR SUBCATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEliminarSubCategoria", function(){

	 var idSubCategoria = $(this).attr("idSubCategoria");

	 swal({
	 	title: '¿Está seguro de borrar la subcategoría?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar subcategoría!'
	 }).then(function(result){

	 	if(result.value){

	 		window.location = "index.php?ruta=subcategorias&idSubCategoria="+idSubCategoria;

	 	}

	 })
})
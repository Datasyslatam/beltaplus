

$("#crear-nuevo-cliente").submit(function(event){
    
    let nombre = $("#nuevoCliente").val()
    let documento = $("#nuevoDocumentoId").val()
    let email = $("#nuevoEmail").val()
    let telefono = $("#nuevoTelefono").val()
    let direccion = $("#nuevaDireccion").val()
    let ciudad = $("#nuevaCiudad").val()
    let fecha_nac = $("#nuevaFechaNacimiento").val()

    // validamos todos los campos
    if (!nombre || !documento || !email || !telefono || !direccion || !ciudad || !fecha_nac) {
        swal({
            type: "error",
            title: "¡Rellenar todos los campos obligatorios!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
        })
        return
    }

    var datos = new FormData();
	datos.append("nuevoCliente", nombre);
	datos.append("nuevoDocumentoId", documento);
	datos.append("nuevoEmail", email);
	datos.append("nuevoTelefono", telefono);
	datos.append("nuevaDireccion", direccion);
    datos.append("nuevaCiudad", ciudad);
	datos.append("nuevaFechaNacimiento", fecha_nac);
	datos.append("guardar", true);

    $.ajax({
		url: "ajax/clientes.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){
            if(respuesta.error == 0){
                const id_nuevo_cliente = respuesta.data;

                swal({
                    type: "success",
                    title: "El cliente ha sido guardado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            CierraPopup("modalAgregarCliente")
                            limpiarCampoModalCliente()
                            refrescarSelectClientes(id_nuevo_cliente)
                        }
                    })
            }else{
                swal({
                    type: "error",
                    title: 'Oops...',
                    text: respuesta.msg,
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                })
            }
     	}

	})

    event.preventDefault();
})

$("#filtrarDocumento").keyup(function(){
    const valor =  $(this).val()
    filtrar_clientes("documento", valor)
})
$("#filtrarEmail").keyup(function(){
    const valor =  $(this).val()
    filtrar_clientes("email", valor)
})
$("#filtrarTelefono").keyup(function(){
    const valor =  $(this).val()
    filtrar_clientes("telefono", valor)
})


$("#filtrar-cliente").submit(function(event){
     
    let item_seleccionar = $("#idClienteSolicitado").val()

    if(item_seleccionar != ""){
        CierraPopup("modalFiltrarCliente")
        limpiarCampoModalCliente()
        refrescarSelectClientes(item_seleccionar)
    }else{
        swal({
            type: "error",
            title: "¡No se ha encontrado usuario!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
        })
    }

    event.preventDefault();
})

function CierraPopup(elemento) {
    $("#"+elemento).modal('hide');//ocultamos el modal
    $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
    //$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}

function refrescarSelectClientes(seleccionar_item = null){
    var datos = new FormData();
	datos.append("mostrarClientes", true);

    $.ajax({
		url: "ajax/clientes.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
        success: function(respuesta){
            let template = `<option value="">Seleccionar cliente</option>`;

            respuesta.forEach(element => {
                template += `<option value="${element.id}">${element.nombre}</option>`
            });

            $("#seleccionarCliente").html(template)

            if(seleccionar_item != null){
                $("#seleccionarCliente").val(seleccionar_item)
            }

        }
    })
}

function filtrar_clientes(item, valor){
    var datos = new FormData();
	datos.append("item", item);
	datos.append("valor", valor);
	datos.append("filtrarCliente", true);
    
    $.ajax({
		url: "ajax/clientes.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
        success: function(respuesta){
            if(respuesta){
                $("#idClienteSolicitado").val(respuesta.id)
                $("#filtrarDocumento").val(respuesta.documento)
                $("#filtrarEmail").val(respuesta.email)
                $("#filtrarTelefono").val(respuesta.telefono)
                $("#filtrarNombre").val(respuesta.nombre)
            }
        }
    })

}

function limpiarCampoModalCliente(){

    /* 
        Limpiar nuevo cliente
    */
    $("#nuevoCliente").val(null)
    $("#nuevoDocumentoId").val(null)
    $("#nuevoEmail").val(null)
    $("#nuevoTelefono").val(null)
    $("#nuevaDireccion").val(null)
    $("#nuevaCiudad").val(null)
    $("#nuevaFechaNacimiento").val(null)

    /* 
        Limpiar filtrar Cliente
    */

    $("#idClienteSolicitado").val(null)
    $("#filtrarDocumento").val(null)
    $("#filtrarEmail").val(null)
    $("#filtrarTelefono").val(null)
    $("#filtrarNombre").val(null)
}
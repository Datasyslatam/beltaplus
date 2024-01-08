/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-ventas.ajax.php",
// 	success:function(respuesta){

// 		console.log("respuesta", respuesta);

// 	}

// })//
var productos_acumulado = {};
$(".tablaVentas").DataTable({
    //"ajax": "ajax/datatable-ventas.ajax.php",
    deferRender: true,
    retrieve: true,
    processing: true,
    language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Registros del _START_ al _END_ de un total de _TOTAL_",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
        },
        oAria: {
            sSortAscending:
                ": Activar para ordenar la columna de manera ascendente",
            sSortDescending:
                ": Activar para ordenar la columna de manera descendente",
        },
    },
});

/*=============================================
AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
=============================================*/
let ajaxRespuestas = [];

$(".tablaVentas tbody").on(
    "click",
    "button.agregarProducto",
    async function () {
        var idProducto = $(this).attr("idProducto");

        $(this).removeClass("btn-primary agregarProducto");
        $(this).addClass("btn-default");

        var datos = new FormData();
        datos.append("id_producto", idProducto);
        datos.append("agregar_producto", true);

        try {
            var respuesta = await $.ajax({
                url: "ajax/productos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
            });
            let cantidad_acumulada = cantidadItems()
            var descripcion = respuesta["descripcion_producto"];
            var stock = respuesta["stock"];
            if (cantidad_acumulada >= 5) {
                var precio = respuesta["precio_compra"];
            } else {
                var precio = respuesta["precio_venta"];
            }
            var id = respuesta["codigo"];
            manipularProductos(id, "añadir", 1, true);
            $(".nuevoProducto .row").each(function () {
                var idCantidadProducto = $(this)
                    .find(".nuevaCantidadProducto")
                    .attr("id");
                var precio = $(this).find(".nuevoPrecioProducto");
                var productoEncontrado = encontrarProducto(idCantidadProducto);
                if (productoEncontrado) {
                    if (cantidad_acumulada >= 5) {
                        nuevoPrecio = productoEncontrado.precio_compra;
                        console.log("entro");
                    } else {
                        console.log("no entro");
                        nuevoPrecio = productoEncontrado.precio_venta;
                    }

                    var cantidad = parseFloat(
                        $(this).find(".nuevaCantidadProducto").val()
                    );
                    precio.val(nuevoPrecio * cantidad);
                }
            });

            if (stock == 0) {
                swal({
                    title: "No hay stock disponible",
                    type: "error",
                    confirmButtonText: "¡Cerrar!",
                });

                $("button[idProducto='" + idProducto + "']").addClass(
                    "btn-primary agregarProducto"
                );

                return;
            }

            // Hacer ambas llamadas AJAX concurrentemente
            var [tallas, colores] = await Promise.all([
                $.ajax({
                    url: "ajax/mostrar-data-preventa.ajax.php",
                    method: "GET",
                    data: {
                        action: "mostrarTalla",
                        itemDatos: idProducto,
                        valorDatos: respuesta["id_talla"],
                    },
                    dataType: "json",
                }),
                $.ajax({
                    url: "ajax/mostrar-data-preventa.ajax.php",
                    method: "GET",
                    data: {
                        action: "mostrarColor",
                        itemDatos: idProducto,
                        valorDatos: respuesta["id_color"],
                    },
                    dataType: "json",
                }),
            ]);

            let tallaEncontrada = (
                tallas.find(
                    (diccionario) => diccionario.id === respuesta["id_talla"]
                ) || {}
            ).talla;
            let colorEncontrado = (
                colores.find(
                    (diccionario) => diccionario.id === respuesta["id_color"]
                ) || {}
            ).color;

            $(".nuevoProducto").append(
                '<div class="row" style="padding:5px 15px">' +
                    "<!-- Descripción del producto -->" +
                    '<div class="col-xs-6" style="padding-right:0px">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="' +
                    idProducto +
                    '"><i class="fa fa-times"></i></button></span>' +
                    '<input type="text" class="form-control nuevaDescripcionProducto" idProducto="' +
                    idProducto +
                    '" name="agregarProducto" value="' +
                    descripcion +
                    " " +
                    colorEncontrado +
                    " " +
                    tallaEncontrada +
                    '" readonly required>' +
                    "</div>" +
                    "</div>" +
                    "<!-- Cantidad del producto -->" +
                    '<div class="col-xs-3">' +
                    '<input id="' +
                    id +
                    '"type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock="' +
                    stock +
                    '" nuevoStock="' +
                    Number(stock - 1) +
                    '" required>' +
                    "</div>" +
                    "<!-- Precio del producto -->" +
                    '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                    '<input type="text" class="form-control nuevoPrecioProducto" precioReal="' +
                    precio +
                    '" name="nuevoPrecioProducto" value="' +
                    precio +
                    '" readonly required>' +
                    "</div>" +
                    "</div>" +
                    "</div>"
            );
            ajaxRespuestas.push(respuesta);
            marcarOferta(id, 1, "añadir");
            sumarTotalPrecios();
            agregarImpuesto();
            listarProductos();
            $(".nuevoPrecioProducto").number(true, 2);

            localStorage.removeItem("quitarProducto");
        } catch (error) {
            console.error("Error en la llamada AJAX:", error);
        }
    }
);

/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA   
=============================================*/

$(".tablaVentas").on("draw.dt", function () {
    if (localStorage.getItem("quitarProducto") != null) {
        var listaIdProductos = JSON.parse(
            localStorage.getItem("quitarProducto")
        );

        for (var i = 0; i < listaIdProductos.length; i++) {
            $(
                "button.recuperarBoton[idProducto='" +
                    listaIdProductos[i]["idProducto"] +
                    "']"
            ).removeClass("btn-default");
            $(
                "button.recuperarBoton[idProducto='" +
                    listaIdProductos[i]["idProducto"] +
                    "']"
            ).addClass("btn-primary agregarProducto");
        }
    }
});

/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");

$(".formularioVenta").on("click", "button.quitarProducto", function () {
    $(this).parent().parent().parent().parent().remove();

    var idProducto = $(this).attr("idProducto");

    /*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
	=============================================*/

    if (localStorage.getItem("quitarProducto") == null) {
        idQuitarProducto = [];
    } else {
        idQuitarProducto.concat(localStorage.getItem("quitarProducto"));
    }

    idQuitarProducto.push({ idProducto: idProducto });

    localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));

    $("button.recuperarBoton[idProducto='" + idProducto + "']").removeClass(
        "btn-default"
    );

    $("button.recuperarBoton[idProducto='" + idProducto + "']").addClass(
        "btn-primary agregarProducto"
    );

    if ($(".nuevoProducto").children().length == 0) {
        $("#nuevoImpuestoVenta").val(0);
        $("#nuevoTotalVenta").val(0);
        $("#totalVenta").val(0);
        $("#nuevoTotalVenta").attr("total", 0);
    } else {
        $(".nuevoProducto .row").each(function () {
            var idCantidadProducto = $(this)
                .find(".nuevaCantidadProducto")
                .attr("id");
            var precio = $(this).find(".nuevoPrecioProducto");
            var productoEncontrado = encontrarProducto(idCantidadProducto);

            if (productoEncontrado) {
                if (cantidad_acumulada >= 7) {
                    nuevoPrecio = productoEncontrado.precio_compra;
                    console.log("entro");
                } else {
                    console.log("no entro");
                    nuevoPrecio = productoEncontrado.precio_venta;
                }

                var cantidad = parseFloat(
                    $(this).find(".nuevaCantidadProducto").val()
                );
                precio.val(nuevoPrecio * cantidad);
            }
        });
        marcarOferta();
        // SUMAR TOTAL DE PRECIOS

        sumarTotalPrecios();

        // AGREGAR IMPUESTO

        agregarImpuesto();

        // AGRUPAR PRODUCTOS EN FORMATO JSON

        listarProductos();
    }
});

/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function () {
    console.log("Aca es");
    numProducto++;

    var datos = new FormData();
    datos.append("traerProductos", "ok");

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            let id = respuesta["codigo"];
            $(".nuevoProducto").append(
                '<div class="row" style="padding:5px 15px">' +
                    "<!-- Descripción del producto -->" +
                    '<div class="col-xs-6" style="padding-right:0px">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto><i class="fa fa-times"></i></button></span>' +
                    '<select class="form-control nuevaDescripcionProducto" id="producto' +
                    numProducto +
                    '" idProducto name="nuevaDescripcionProducto" required>' +
                    "<option>Seleccione el producto</option>" +
                    "</select>" +
                    "</div>" +
                    "</div>" +
                    "<!-- Cantidad del producto -->" +
                    '<div class="col-xs-3 ingresoCantidad">' +
                    '<input id="' +
                    id +
                    '"type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>' +
                    "</div>" +
                    "<!-- Precio del producto -->" +
                    '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                    '<input type="text" class="form-control nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto" readonly required>' +
                    "</div>" +
                    "</div>" +
                    "</div>"
            );
            // AGREGAR LOS PRODUCTOS AL SELECT

            respuesta.forEach(funcionForEach);

            function funcionForEach(item, index) {
                if (item.stock != 0) {
                    $("#producto" + numProducto).append(
                        '<option idProducto="' +
                            item.id +
                            '" value="' +
                            item.descripcion +
                            '">' +
                            item.descripcion +
                            "</option>"
                    );
                }
            }
            marcarOferta();

            // SUMAR TOTAL DE PRECIOS

            sumarTotalPrecios();

            // AGREGAR IMPUESTO

            agregarImpuesto();

            // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

            $(".nuevoPrecioProducto").number(true, 2);
        },
    });
});

/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioVenta").on(
    "change",
    "select.nuevaDescripcionProducto",
    function () {
        var nombreProducto = $(this).val();

        var nuevaDescripcionProducto = $(this)
            .parent()
            .parent()
            .parent()
            .children()
            .children()
            .children(".nuevaDescripcionProducto");

        var nuevoPrecioProducto = $(this)
            .parent()
            .parent()
            .parent()
            .children(".ingresoPrecio")
            .children()
            .children(".nuevoPrecioProducto");

        var nuevaCantidadProducto = $(this)
            .parent()
            .parent()
            .parent()
            .children(".ingresoCantidad")
            .children(".nuevaCantidadProducto");

        var datos = new FormData();
        datos.append("nombreProducto", nombreProducto);

        $.ajax({
            url: "ajax/productos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
                $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
                $(nuevaCantidadProducto).attr(
                    "nuevoStock",
                    Number(respuesta["stock"]) - 1
                );
                if (cantidad_acumulada >= 5) {
                    $(nuevoPrecioProducto).val(respuesta["precio_compra"]);
                } else {
                    $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
                }
                $(nuevoPrecioProducto).attr(
                    "precioReal",
                    respuesta["precio_venta"]
                );

                // AGRUPAR PRODUCTOS EN FORMATO JSON
                listarProductos();
                marcarOferta();
            },
        });
    }
);
function encontrarProducto(id) {
    let elementoEncontrado;
    for (var i = 0; i < ajaxRespuestas.length; i++) {
        var producto = ajaxRespuestas[i];
        if (producto.codigo === id) {
            elementoEncontrado = producto;
            return elementoEncontrado;
        }
    }
}
function cantidadItems() {
    let valor_acumulado = 0; 
    for (let clave in productos_acumulado) {
            valor_acumulado += productos_acumulado[clave];
            console.log(productos_acumulado[clave]);
    }
    return valor_acumulado
}

function manipularProductos(codigo, opcion, valor, tipoModificacion) {
    switch (opcion) {
        case "añadir":
            productos_acumulado[codigo] = valor;
            break;

        case "modificar":
            if (tipoModificacion) {
                productos_acumulado[codigo] += valor;
            } else {
                productos_acumulado[codigo] -= valor;
            }
            break;

        case "eliminar":
            delete productos_acumulado[codigo];
            break;

        default:
            console.log("Opción no válida");
    }
}

function marcarOferta() {
    let valores = $('input[name="nuevoPrecioProducto"]');
    let cantidades = $('input[name="nuevaCantidadProducto"]'); //Obtiene los valores de la cantidad
    let suma = 0;
    cantidades.each(function () {
        let valor = parseFloat($(this).val()) || 0;
        suma += valor;
    });
    let cantidad_acumulada = cantidadItems();
    if (cantidad_acumulada >= 6) {
        valores.each(function () {
            $(this).css("background-color", "#7AB4AD");
        });
    } else {
        valores.each(function () {
            $(this).css("background-color", "#eee");
        });
    }
}
/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/
$(".formularioVenta").on("change", "input.nuevaCantidadProducto", function () {
    var codigoBuscado = $(this).attr("id");
    var precio = $(this)
        .parent()
        .parent()
        .children(".ingresoPrecio")
        .children()
        .children(".nuevoPrecioProducto");

    var stock = parseFloat($(this).attr("stock"));
    var cantidad = parseFloat($(this).val());
    var stockActual = parseFloat($(this).attr("nuevoStock"));
    var nuevoStock = stock - cantidad;
    let cantidad_acumulada = cantidadItems();
    let modificacion = (nuevoStock < stockActual) ? true : false;
    $(".nuevoProducto .row").each(function () {
        var idCantidadProducto = $(this)
            .find(".nuevaCantidadProducto")
            .attr("id");
        var precio = $(this).find(".nuevoPrecioProducto");
        var productoEncontrado = encontrarProducto(idCantidadProducto);

        if (productoEncontrado) {
            if (
                (cantidad_acumulada >= 5 && nuevoStock < stockActual) ||
                (cantidad_acumulada >= 7 && nuevoStock > stockActual)
            ) {
                nuevoPrecio = productoEncontrado.precio_compra;
                console.log("entro");
            } else {
                console.log("no entro");
                nuevoPrecio = productoEncontrado.precio_venta;
            }

            var cantidad = parseFloat(
                $(this).find(".nuevaCantidadProducto").val()
            );
            precio.val(nuevoPrecio * cantidad);
        }
    });

    $(this).attr("nuevoStock", nuevoStock);

    if (cantidad > stock) {
        $(this).val(0);
        $(this).attr("nuevoStock", stock);
        precio.val(0);

        sumarTotalPrecios();

        swal({
            title: "La cantidad supera el Stock",
            text: "¡Sólo hay " + stock + " unidades!",
            type: "error",
            confirmButtonText: "¡Cerrar!",
        });

        return;
    }
    manipularProductos(codigoBuscado, "modificar", 1, modificacion)
    marcarOferta();

    // SUMAR TOTAL DE PRECIOS
    sumarTotalPrecios();

    // AGREGAR IMPUESTO
    agregarImpuesto();

    // AGRUPAR PRODUCTOS EN FORMATO JSON
    listarProductos();
});

/*=============================================
SUMAR TODOS LOS PRECIOS
=============================================*/

function sumarTotalPrecios() {
    var precioItem = $(".nuevoPrecioProducto");

    var arraySumaPrecio = [];

    for (var i = 0; i < precioItem.length; i++) {
        arraySumaPrecio.push(Number($(precioItem[i]).val()));
    }

    function sumaArrayPrecios(total, numero) {
        return total + numero;
    }

    var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);

    $("#nuevoTotalVenta").val(sumaTotalPrecio);
    $("#totalVenta").val(sumaTotalPrecio);
    $("#nuevoTotalVenta").attr("total", sumaTotalPrecio);
}

/*=============================================
FUNCIÓN AGREGAR IMPUESTO
=============================================*/

function agregarImpuesto() {
    var impuesto = $("#nuevoImpuestoVenta").val();
    var precioTotal = $("#nuevoTotalVenta").attr("total");

    var precioImpuesto = Number((precioTotal * impuesto) / 100);

    var totalConImpuesto = Number(precioImpuesto) + Number(precioTotal);

    $("#nuevoTotalVenta").val(totalConImpuesto);

    $("#totalVenta").val(totalConImpuesto);

    $("#nuevoPrecioImpuesto").val(precioImpuesto);

    $("#nuevoPrecioNeto").val(precioTotal);
}

/*=============================================
CUANDO CAMBIA EL IMPUESTO
=============================================*/

$("#nuevoImpuestoVenta").change(function () {
    agregarImpuesto();
});

/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/

$("#nuevoTotalVenta").number(true, 2);

/*=============================================
SELECCIONAR MÉTODO DE PAGO
=============================================*/

$("#nuevoMetodoPago").change(function () {
    var metodo = $(this).val();

    if (metodo == "Efectivo") {
        $(this).parent().parent().removeClass("col-xs-6");

        $(this).parent().parent().addClass("col-xs-4");

        $(this)
            .parent()
            .parent()
            .parent()
            .children(".cajasMetodoPago")
            .html(
                '<div class="col-xs-4">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                    '<input type="text" class="form-control" id="nuevoValorEfectivo" placeholder="000000" required>' +
                    "</div>" +
                    "</div>" +
                    '<div class="col-xs-4" id="capturarCambioEfectivo" style="padding-left:0px">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                    '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>' +
                    "</div>" +
                    "</div>"
            );

        // Agregar formato al precio

        $("#nuevoValorEfectivo").number(true, 2);
        $("#nuevoCambioEfectivo").number(true, 2);

        // Listar método en la entrada
        listarMetodos();
    } else {
        $(this).parent().parent().removeClass("col-xs-4");

        $(this).parent().parent().addClass("col-xs-6");

        $(this)
            .parent()
            .parent()
            .parent()
            .children(".cajasMetodoPago")
            .html(
                '<div class="col-xs-6" style="padding-left:0px">' +
                    '<div class="input-group">' +
                    '<input type="number" min="0" class="form-control" id="nuevoCodigoTransaccion" placeholder="Código transacción"  required>' +
                    '<span class="input-group-addon"><i class="fa fa-lock"></i></span>' +
                    "</div>" +
                    "</div>"
            );
    }
});

/*=============================================
CAMBIO EN EFECTIVO
=============================================*/
$(".formularioVenta").on("change", "input#nuevoValorEfectivo", function () {
    var efectivo = $(this).val();

    var cambio = Number(efectivo) - Number($("#nuevoTotalVenta").val());

    var nuevoCambioEfectivo = $(this)
        .parent()
        .parent()
        .parent()
        .children("#capturarCambioEfectivo")
        .children()
        .children("#nuevoCambioEfectivo");

    nuevoCambioEfectivo.val(cambio);
});

/*=============================================
CAMBIO TRANSACCIÓN
=============================================*/
$(".formularioVenta").on("change", "input#nuevoCodigoTransaccion", function () {
    // Listar método en la entrada
    listarMetodos();
});

/*=============================================
LISTAR TODOS LOS PRODUCTOS
=============================================*/

function listarProductos() {
    var listaProductos = [];
    var descripcion = $(".nuevaDescripcionProducto");
    var cantidad = $(".nuevaCantidadProducto");
    var precio = $(".nuevoPrecioProducto");

    for (var i = 0; i < descripcion.length; i++) {
        listaProductos.push({
            id: $(descripcion[i]).attr("idProducto"),
            descripcion: $(descripcion[i]).val(),
            cantidad: $(cantidad[i]).val(),
            stock: $(cantidad[i]).attr("nuevoStock"),
            precio: $(precio[i]).attr("precioReal"),
            total: $(precio[i]).val(),
        });
    }

    $("#listaProductos").val(JSON.stringify(listaProductos));
}

/*=============================================
LISTAR MÉTODO DE PAGO
=============================================*/

function listarMetodos() {
    var listaMetodos = "";

    if ($("#nuevoMetodoPago").val() == "Efectivo") {
        $("#listaMetodoPago").val("Efectivo");
    } else {
        $("#listaMetodoPago").val(
            $("#nuevoMetodoPago").val() +
                "-" +
                $("#nuevoCodigoTransaccion").val()
        );
    }
}

/*=============================================
BOTON EDITAR VENTA
=============================================*/
$(".tablas").on("click", ".btnEditarVenta", function () {
    var idVenta = $(this).attr("idVenta");

    window.location = "index.php?ruta=editar-venta&idVenta=" + idVenta;
});

/*=============================================
FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
=============================================*/

function quitarAgregarProducto() {
    //Capturamos todos los id de productos que fueron elegidos en la venta
    var idProductos = $(".quitarProducto");

    //Capturamos todos los botones de agregar que aparecen en la tabla
    var botonesTabla = $(".tablaVentas tbody button.agregarProducto");

    //Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la venta
    for (var i = 0; i < idProductos.length; i++) {
        //Capturamos los Id de los productos agregados a la venta
        var boton = $(idProductos[i]).attr("idProducto");

        //Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
        for (var j = 0; j < botonesTabla.length; j++) {
            if ($(botonesTabla[j]).attr("idProducto") == boton) {
                $(botonesTabla[j]).removeClass("btn-primary agregarProducto");
                $(botonesTabla[j]).addClass("btn-default");
            }
        }
    }
}

/*=============================================
CADA VEZ QUE CARGUE LA TABLA CUANDO NAVEGAMOS EN ELLA EJECUTAR LA FUNCIÓN:
=============================================*/

$(".tablaVentas").on("draw.dt", function () {
    quitarAgregarProducto();
});

/*=============================================
BORRAR VENTA
=============================================*/
$(".tablas").on("click", ".btnEliminarVenta", function () {
    var idVenta = $(this).attr("idVenta");

    swal({
        title: "¿Está seguro de borrar la venta?",
        text: "¡Si no lo está puede cancelar la accíón!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Si, borrar venta!",
    }).then(function (result) {
        if (result.value) {
            window.location = "index.php?ruta=ventas&idVenta=" + idVenta;
        }
    });
});

/*=============================================
IMPRIMIR FACTURA
=============================================*/

$(".tablas").on("click", ".btnImprimirFactura", function () {
    var codigoVenta = $(this).attr("codigoVenta");

    window.open(
        "extensiones/tcpdf/pdf/factura.php?codigo=" + codigoVenta,
        "_blank"
    );
});
$(".tablas").on("click", ".btnImprimirFacturaCarta", function () {
    var codigoVenta = $(this).attr("codigoVenta");

    window.open(
        "extensiones/tcpdf/pdf/factura-carta.php?codigo=" + codigoVenta,
        "_blank"
    );
});
/*=============================================
RANGO DE FECHAS
=============================================*/

$("#daterange-btn").daterangepicker(
    {
        ranges: {
            Hoy: [moment(), moment()],
            Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Últimos 7 días": [moment().subtract(6, "days"), moment()],
            "Últimos 30 días": [moment().subtract(29, "days"), moment()],
            "Este mes": [moment().startOf("month"), moment().endOf("month")],
            "Último mes": [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ],
        },
        startDate: moment(),
        endDate: moment(),
    },
    function (start, end) {
        $("#daterange-btn span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );

        var fechaInicial = start.format("YYYY-MM-DD");

        var fechaFinal = end.format("YYYY-MM-DD");

        var capturarRango = $("#daterange-btn span").html();

        localStorage.setItem("capturarRango", capturarRango);

        window.location =
            "index.php?ruta=ventas&fechaInicial=" +
            fechaInicial +
            "&fechaFinal=" +
            fechaFinal;
    }
);

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on(
    "click",
    function () {
        localStorage.removeItem("capturarRango");
        localStorage.clear();
        window.location = "ventas";
    }
);

/*=============================================
CAPTURAR HOY
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function () {
    var textoHoy = $(this).attr("data-range-key");

    if (textoHoy == "Hoy") {
        var d = new Date();

        var dia = d.getDate();
        var mes = d.getMonth() + 1;
        var año = d.getFullYear();

        // if(mes < 10){

        // 	var fechaInicial = año+"-0"+mes+"-"+dia;
        // 	var fechaFinal = año+"-0"+mes+"-"+dia;

        // }else if(dia < 10){

        // 	var fechaInicial = año+"-"+mes+"-0"+dia;
        // 	var fechaFinal = año+"-"+mes+"-0"+dia;

        // }else if(mes < 10 && dia < 10){

        // 	var fechaInicial = año+"-0"+mes+"-0"+dia;
        // 	var fechaFinal = año+"-0"+mes+"-0"+dia;

        // }else{

        // 	var fechaInicial = año+"-"+mes+"-"+dia;
        //    	var fechaFinal = año+"-"+mes+"-"+dia;

        // }

        dia = ("0" + dia).slice(-2);
        mes = ("0" + mes).slice(-2);

        var fechaInicial = año + "-" + mes + "-" + dia;
        var fechaFinal = año + "-" + mes + "-" + dia;

        localStorage.setItem("capturarRango", "Hoy");

        window.location =
            "index.php?ruta=ventas&fechaInicial=" +
            fechaInicial +
            "&fechaFinal=" +
            fechaFinal;
    }
});

/*=============================================
ABRIR ARCHIVO XML EN NUEVA PESTAÑA
=============================================*/

$(".abrirXML").click(function () {
    var archivo = $(this).attr("archivo");
    window.open(archivo, "_blank");
});

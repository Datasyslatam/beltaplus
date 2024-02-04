$(document).ready(function () {
    window.addEventListener("load", function () {
      adjustButtonWidths();
    });
  
    window.addEventListener("resize", function () {
      adjustButtonWidths();
    });
  
    llenarBotonesSeleccionables();
    filtrarDatos();
  });
  
  
  //Accion de eliminar filtro de campos input
  $(".eliminar-filtro").click(function () {
    var contenedor = $(this).parents();
    var tipo = contenedor.data("id");
  
    mostrarElemento("#cont-colores", false, [tipo]);
  
    if (tipo === "subcategoria" || tipo === "categorias") {
      mostrarElemento("#cont-subcategoria", false, [tipo]);
    }
  
    if (tipo === "categorias") {
      mostrarElemento("#cont-categorias", false, [tipo]);
    }
  
    llenarBotonesSeleccionables();
  
    const tipos = ["categorias", "subcategoria", "colores"];
    let index = tipos.indexOf(tipo);
    var tipo_actual = tipos[index - 1];
    if (tipo_actual) {
      filtrarDatos(tipo_actual)
    }else{
      filtrarDatos()
    }
  });
  
  //Funcion usada para ajustar los botones
  function adjustButtonWidths() {
    var buttonGroup = document.getElementById("button-group");
    var buttons = buttonGroup.getElementsByClassName("btn");
    var maxWidth = 0;
  
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].style.width = "auto";
      var buttonWidth = buttons[i].offsetWidth;
      if (buttonWidth > maxWidth) {
        maxWidth = buttonWidth;
      }
    }
  
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].style.width = maxWidth + "px";
    }
  }
  
  // Parar rellenar las multiples opciones
  function fillButtonGroup(datos, tipo) {
    let template = ``;
    datos.forEach((elem) => {
      if (elem.categoria != "undefined" && typeof elem.nombre == "undefined") {
        template += `<button type="button" class="btn btn-outline-secondary mx-1 btn-seleccionable" data-option="${elem.id}" data-tipo = "${tipo}" onclick="seleccionarOpcion(this)">${elem.categoria}</button>`;
      } else {  // Valido si es la cetgoria es Colores para pintar los botones con su color respectivo
        if(tipo == "colores"){
          template += `<button type="button" class="btn btn-outline-secondary mx-1 btn-seleccionable" data-option="${elem.id}" data-tipo = "${tipo}" style="background-color:${elem.cod_color}" onclick="seleccionarOpcion(this)">${elem.nombre}</button>`;
        }else{ 
          template += `<button type="button" class="btn btn-outline-secondary mx-1 btn-seleccionable" data-option="${elem.id}" data-tipo = "${tipo}" onclick="seleccionarOpcion(this)">${elem.nombre}</button>`;
        }
      }
    });
  
    $("#button-group").html(template);
  }
  //funcion para obtener los datos de las categorias
  
  //Funcion para crear la estructura de los datos de los botones
  function llenarBotonesSeleccionables() {
    $.ajax({
      url: "vistas/modulos/mostrar_categorias_a.php",
      type: "GET",
      dataType: "json",
      success: function (data) {
        CATEGORIAS = data.categorias1;
        SUB_CATEGORIAS = data.subCategorias1;
        COLORES = data.colores1;
        var tipo = null;
        $(".filterSearch").each(function () {
          if ($(this).hasClass("hidden")) {
            tipo = $(this).data("id");
            return false;
          }
        });
  
        if (tipo) {
          var datos = [];
          //Categorias
          switch (tipo) {
            case "categorias":
              fillButtonGroup(CATEGORIAS, tipo);
              break;
            case "subcategoria":
              const idcat = $("#categorias").data("id");
  
              datos = SUB_CATEGORIAS.filter((sub) => {
                return sub.categoria_id == idcat;
              });
  
              fillButtonGroup(datos, tipo);
  
              if (datos.length == 0) {
                $("#button-group").html(
                  `<p class='not-found-subcategory'><b>No se encontro subcategoria para esta categoria.<b></p>`
                );
              }
  
              break;
            default:
              for (const key of COLORES) {
                datos.push({
                  id: key["id"],
                  nombre: key["color"],
                  cod_color: key["cod_color"],      // Codigo hex del color para pintar Boton del nombre del color
                });
              }
  
              fillButtonGroup(datos, tipo);
              break;
          }
        }
      },
      error: function (xhr, status, error) {
        console.log("Error en la solicitud Ajax: " + error);
      },
    });
  }
  
  //Funcion para aparecer la opcion en el text y refrescar la tabla con el filtro
  function seleccionarOpcion(elemento) {
    if (elemento) {
      var id = $(elemento).data("option");
      var tipo = $(elemento).data("tipo");
      var texto = $(elemento).text();
  
      mostrarElemento("#cont-" + tipo);
      $("#" + tipo).val(texto);
      $("#" + tipo).data("id", id);
  
      if (tipo != "colores") {
        llenarBotonesSeleccionables();
      } else {
        fillButtonGroup([], "");
      }
      filtrarDatos(tipo);
    }
  }
  
  function filtrarDatos(elemento) {
    categoria = "";
    subcategoria = "";
    colores = "";
    console.log("INICIO");
  
    if (["categorias", "subcategoria", "colores"].includes(elemento)) {
      categoria = $("#categorias").data("id");
    }
  
    if (["subcategoria", "colores"].includes(elemento)) {
      subcategoria = $("#subcategoria").data("id");
    }
  
    if (["colores"].includes(elemento)) {
      colores = $("#colores").data("id");
    }
  
    
    var datos = new FormData();
    datos.append("id_categoria", categoria);
    datos.append("id_subcategoria", subcategoria);
    datos.append("id_calor", colores);
  
    $.ajax({
      url: "ajax/filtro-productos.ajax.php",
      type: "POST",
      cache: false,
      data: datos,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (resp) {
        if (resp) {
            let template = ``

            resp.forEach(element => {

                let stock_html = ""
                const stock_producto = element.stock

                if(stock_producto <= 10){
                    stock_html= `<button class='btn btn-danger'>${stock_producto}</button>`;
                }else if(stock_producto > 11 && stock_producto <= 15){
                    stock_html = `<button class='btn btn-warning'>${stock_producto}</button>`;
                }else{
                    stock_html = `<button class='btn btn-success'>${stock_producto}</button>`;
                }
                
                let accion = `<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='${element.id_producto}'>Agregar</button></div>`
                

                template += `<tr>
                        <td>${element.codigo}</td>
                        <td>${element.categoria}</td>
                        <td>${element.subcategoria}</td>
                        <td>${element.color}</td>
                        <td>${element.talla}</td>
                        <td>${stock_html}</td>
                        <td>${accion}</td>
                        </tr>`;
            });

            $(".tablaVentas tbody").html(template);
        }
      },
    });
  }
  
  function mostrarElemento(elemento, mostrar = true, clean_field = []) {
    if (mostrar) {
      $(elemento).removeClass("hidden");
      return false;
    }
  
    clean_field.forEach((element) => {
      $("#" + element).val("");
    });
  
    $(elemento).addClass("hidden");
  }
  
  function mostrarTabla(tabla) {
    mostrarElemento("#tabla-filtro-tallas", false);
    mostrarElemento("#tabla-filtro-precios", false);
  
    mostrarElemento("#" + tabla);
  }
  
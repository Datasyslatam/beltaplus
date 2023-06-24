const CATEGORIAS = [
    {
      name: "Categoria1",
      id: 1
    },
    {
      name: "Categoria2",
      id: 2
    },
    {
      name: "Categoria3",
      id: 3
    },
    {
      name: "Categoria4",
      id: 4
    },
    {
      name: "Categoria5",
      id: 5
    },
    {
      name: "Categoria6",
      id: 6
    }
]

const SUB_CATEGORIAS = [
    {
        name: "Subcategoria 1-1",
        id: 1,
        categoria: 1
    },
    {
        name: "Subcategoria 1-2",
        id: 2,
        categoria: 1
    },
    {
        name: "Subcategoria 1-3",
        id: 3,
        categoria: 1
    },
    {
        name: "Subcategoria 2-1",
        id: 4,
        categoria: 2
    },
    {
        name: "Subcategoria 2-2",
        id: 5,
        categoria: 2
    },
    {
        name: "Subcategoria 3-1",
        id: 6,
        categoria: 3
    }
]

const COLORES = {
    Azul: 1,
    Rojo: 2,
    Amarillo: 3,
    Azul_Oscuro: 4,
    Violeta: 5,
    Marron: 6,
    Negro: 7,
    Blanco: 8
}


$(document).ready(function() {
    window.addEventListener('load', function () {
      adjustButtonWidths();
    })
    
    window.addEventListener('resize', function () {
      adjustButtonWidths();
    })
      
    llenarBotonesSeleccionables()
  
  });
  
  //Accion de eliminar filtro de campos input
  $(".eliminar-filtro").click(function(){
    var contenedor =  $(this).parents();
    var tipo = contenedor.data("id")
    
    mostrarElemento("#cont-colores", false, [tipo])
    
    if (tipo === "subcategoria" || tipo === "categorias") {
      mostrarElemento("#cont-subcategoria", false, [tipo])
    }
  
    if(tipo === "categorias"){
      mostrarElemento("#cont-categorias", false, [tipo])
    }
  
    llenarBotonesSeleccionables()
  
  })
  
  //Funcion usada para ajustar los botones
  function adjustButtonWidths() {
    var buttonGroup = document.getElementById('button-group');
    var buttons = buttonGroup.getElementsByClassName('btn');
    var maxWidth = 0;
  
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].style.width = 'auto';
      var buttonWidth = buttons[i].offsetWidth;
      if (buttonWidth > maxWidth) {
        maxWidth = buttonWidth;
      }
    }
  
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].style.width = maxWidth + 'px';
    }
  }
  
  // Parar rellenar las multiples opciones
  function fillButtonGroup(datos, tipo) {
    let template = ``
    
    datos.forEach(elem => {
      template +=  `<button type="button" class="btn btn-outline-secondary mx-1 btn-seleccionable" data-option="${elem.id}" data-tipo = "${tipo}" onclick="seleccionarOpcion(this)">${elem.name}</button>`
    })
    
    $("#button-group").html(template)
  }
  
  //Funcion para crear la estructura de los datos de los botones
  function llenarBotonesSeleccionables() {
      var tipo = null
      $('.filterSearch').each(function() {
        if ($(this).hasClass('hidden')) {
          tipo = $(this).data('id');
          return false
        }
      });
  
      if (tipo) {
        var datos = []
        //Categorias
        switch (tipo) {
          case "categorias":
  
            fillButtonGroup(CATEGORIAS, tipo)
            break;
          case "subcategoria":
            const idcat =  $("#categorias").data("id")
            
            datos =  SUB_CATEGORIAS.filter(sub => {
              return sub.categoria == idcat
            })
  
            fillButtonGroup(datos, tipo)

            if (datos.length == 0){
                console.log(tipo);
                $("#button-group").html(`<p class='not-found-subcategory'><b>No se encontro subcategoria para esta categoria.<b></p>`)
            }

            break;
          default:
  
            for (const key in COLORES) {
              datos.push({
                id: COLORES[key],
                name:  key
              })
            }
  
            fillButtonGroup(datos, tipo)
            break;
        }
  
      }
  }
  
  //Funcion para aparecer la opcion en el text y refrescar la tabla con el filtro
  function seleccionarOpcion(elemento) {
    
    if (elemento) {
      var id = $(elemento).data('option')
      var tipo = $(elemento).data('tipo')
      var texto = $(elemento).text()
  
      mostrarElemento('#cont-'+tipo)
      $('#'+tipo).val(texto)
      $('#'+tipo).data("id", id)
  
      if(tipo != "colores"){
        llenarBotonesSeleccionables()
      }else{
        fillButtonGroup([], "")
      }
      
    }
  }
  
  function mostrarElemento(elemento, mostrar = true, clean_field = []) {
    
    if(mostrar){
      $(elemento).removeClass('hidden');
      return false
    }
  
    clean_field.forEach(element => {
      $("#"+element).val("")
    });
  
    $(elemento).addClass('hidden');
  
}

function mostrarTabla(tabla) {

    mostrarElemento("#tabla-filtro-tallas", false)
    mostrarElemento("#tabla-filtro-precios", false)
    
    mostrarElemento("#"+tabla)
    
}


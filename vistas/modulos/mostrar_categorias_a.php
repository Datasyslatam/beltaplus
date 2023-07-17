<?php
//Extraer la informacion de la consulta}
    require_once "../../modelos/categorias.modelo.php";
    //Mediante las clases modeloCategorias podemos hacer la consulta por tablas 
    $categorias = ModeloCategorias::mdlMostrarCategorias("categorias",null,null);
    $subCategorias = ModeloCategorias::mdlMostrarCategorias("subcategorias",null,null);
    $colores = ModeloCategorias::mdlMostrarCategorias("colores",null,null);
    $response = array(
        "categorias1" => $categorias,
        "subCategorias1" => $subCategorias,
        "colores1" => $colores
    );
    echo json_encode($response);

    //
?>
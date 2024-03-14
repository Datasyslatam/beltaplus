# Sistema de Inventario para Marca de Ropa

Este es un proyecto de sistema de inventario desarrollado en PHP utilizando el patrón de arquitectura Modelo-Vista-Controlador (MVC). El propósito principal de este sistema es gestionar el inventario de una marca de ropa, permitiendo el seguimiento de productos, control de existencias, gestión de pedidos, entre otras funcionalidades relacionadas con la gestión de inventario.

Este repositorio es un *fork* de un repositorio existente, donde se realizarán mejoras y adiciones para satisfacer las necesidades específicas de la marca de ropa. Se trabajarán en ramas separadas para cada funcionalidad o mejora, y se realizarán *pull requests* al repositorio original una vez que las funcionalidades estén completas y probadas.

## Estructura del Proyecto

El proyecto sigue una estructura MVC típica para separar las preocupaciones y mantener un código limpio y modularizado. A continuación, se presenta una breve descripción de cada directorio:

- **`ajax/`**: Contiene los archivos relacionados con las solicitudes AJAX para interacciones asíncronas en la aplicación.
- **`controladores/`**: Contiene los controladores que manejan las solicitudes HTTP y coordinan las interacciones entre el modelo y la vista.
- **`extensiones/`**: Directorio para extensiones o complementos adicionales, si es necesario.
- **`modelos/`**: Contiene los modelos que representan la lógica de negocio y manejan las interacciones con la base de datos.
- **`vistas/`**: Contiene las vistas que se encargan de la presentación de la información al usuario.
- **`xml/`**: Archivos XML relacionados con la configuración o intercambio de datos, si es necesario.
- **`.httpacces`**: Archivo de configuración para el servidor web, como Apache, que puede incluir reglas de redirección u otras configuraciones.
- **`index.php`**: Punto de entrada de la aplicación.
- **`README.md`**: Este archivo que estás leyendo actualmente.

## Requisitos del Sistema

- PHP 7.x
- Servidor web (Apache, Nginx, etc.)
- MySQL o un sistema de gestión de bases de datos compatible.

## Configuración

1. Clona este repositorio en tu máquina local.
2. Configura tu servidor web para que el directorio raíz apunte a la carpeta `public/`.
3. Crea una base de datos MySQL y ejecuta los scripts proporcionados en el directorio `database/` para configurar las tablas necesarias.
4. Actualiza el archivo `config/database.php` con los detalles de conexión a tu base de datos.

## Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).

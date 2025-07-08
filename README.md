# Sistema de Gestión de Inventario

Este proyecto fue desarrollado como parte del proceso de formación del Tecnólogo en Análisis y Desarrollo de Software del SENA.

## Funcionalidades

- Registro de usuarios (Create)
- Consulta de usuarios (Read)
- Edición de usuarios (Update)
- Eliminación de usuarios (Delete)

##  Tecnologías utilizadas

- PHP
- MySQL
- HTML y CSS
- XAMPP
- Git y GitHub

##  Perfiles de Usuario

- **Administrador:** puede registrar, editar y eliminar usuarios, gestionar inventarios.
- **Cliente:** puede consultar productos e inventario disponible.
## Estructura de Archivos del Proyecto

supermarketConexion/
├── conexion.php         // Archivo de conexión a la base de datos
├── index.php            // Formulario de registro y lista de usuarios
├── ingresarDatos.php    // Inserta nuevos usuarios
├── eliminar.php         // Elimina usuarios existentes
├── editar.php           // Edita los datos de un usuario

##  Requisitos para ejecutar el proyecto

1. Tener instalado XAMPP o similar.
2. Colocar la carpeta `supermarketConexion` dentro de la carpeta `htdocs`.
3. Crear una base de datos en phpMyAdmin con el nombre `supermercado`.
4. Crear la tabla `usuario` con los siguientes campos:

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(100)
);

## 🚀 Cómo usar el proyecto

1. Inicia el servidor desde XAMPP (activar Apache y MySQL).
2. Abre tu navegador y entra a: `http://localhost/supermarketConexion/index.php`.
3. Registra usuarios desde el formulario.
4. Usa los botones de "Editar" o "Eliminar" para gestionar los registros.



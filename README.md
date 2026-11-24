# COMBOX2

Aplicación web para el registro, autenticación y gestión de usuarios, desarrollada como parte de un proyecto académico.

## 1. Requisitos

Para ejecutar el proyecto se necesita:

- Windows.
- XAMPP con Apache.
- PHP con las siguientes extensiones habilitadas:
  - `pgsql`
  - `pdo_pgsql`
  - `curl`
  - `fileinfo`
- Conexión a Internet.
- Acceso al proyecto de Supabase utilizado por la aplicación.

## 2. Instalación

1. Instalar y ejecutar XAMPP.
2. Abrir la carpeta `htdocs` de XAMPP.
3. Clonar el repositorio:

   ```bash
   git clone https://github.com/alejo24lt-code/combox2.git
   ```

4. Iniciar Apache desde el panel de control de XAMPP.
5. Verificar que las extensiones PHP requeridas estén habilitadas en el archivo `php.ini` de XAMPP.

## 3. Configuración de Supabase

El proyecto utiliza Supabase para gestionar la base de datos PostgreSQL y almacenar las fotografías de perfil.

Para ejecutar correctamente la aplicación, es necesario configurar las siguientes variables de entorno en el computador donde se instalará el proyecto:

| Variable | Descripción |
|---|---|
| `SUPABASE_DB_HOST` | Servidor de PostgreSQL. |
| `SUPABASE_DB_PORT` | Puerto de conexión de PostgreSQL. |
| `SUPABASE_DB_USER` | Usuario de PostgreSQL. |
| `SUPABASE_DB_NAME` | Nombre de la base de datos. |
| `SUPABASE_DB_PASSWORD` | Contraseña de PostgreSQL. |
| `SUPABASE_URL` | URL del proyecto Supabase. |
| `SUPABASE_SECRET_KEY` | Clave secreta utilizada para acceder a Supabase Storage. |
| `SUPABASE_BUCKET` | Nombre del bucket donde se almacenan las fotografías. |

### ¿Dónde obtener los valores?

Los valores corresponden al proyecto de Supabase utilizado por la aplicación:

- **Base de datos:** consultar los datos de conexión en la sección de conexión de PostgreSQL de Supabase.
- **URL y clave secreta:** consultar la configuración de API del proyecto.
- **Bucket:** utilizar el nombre del almacenamiento configurado para las fotografías de perfil.

Las credenciales deben ser proporcionadas por el responsable del proyecto y no deben publicarse en GitHub.

### ¿Cómo configurar las variables en Windows?

1. Abrir el menú Inicio de Windows.
2. Buscar **Editar las variables de entorno del sistema**.
3. Seleccionar la opción **Variables de entorno**.
4. En la sección **Variables de usuario**, seleccionar **Nueva**.
5. Escribir el nombre de cada variable y su valor correspondiente.
6. Repetir el procedimiento hasta configurar las ocho variables.
7. Guardar los cambios.
8. Reiniciar Apache desde el panel de control de XAMPP.

**Importante:** La aplicación obtiene estos valores mediante `getenv()`. Por esta razón, deben configurarse como variables de entorno de Windows. No basta con escribirlas en un archivo `.env`.

No es necesario crear archivos adicionales ni carpetas privadas para esta configuración.

## 4. Ejecución

Una vez instalado y configurado el proyecto:

1. Iniciar Apache desde XAMPP.
2. Abrir un navegador web.
3. Ingresar a la siguiente dirección:

   `http://localhost/combox2/`

Desde la página principal se puede acceder a las opciones disponibles de la aplicación.

## 5. Funcionalidades

- Registro de usuarios.
- Inicio y cierre de sesión.
- Recuperación de contraseña.
- Listado y consulta de usuarios.
- Edición de información de usuarios.
- Actualización de fotografías de perfil.
- Almacenamiento de fotografías en Supabase Storage.
- Conexión a una base de datos PostgreSQL en Supabase.

## 6. Consideraciones de seguridad

- Las contraseñas y claves secretas de Supabase son privadas.
- No se deben publicar credenciales en GitHub.
- Las variables de entorno deben configurarse localmente en cada computador donde se ejecute el proyecto.
- El proyecto utiliza un servicio de Supabase externo, por lo que necesita conexión a Internet.




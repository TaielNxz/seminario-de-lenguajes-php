PHP, React y API Rest
=====================

## Iniciar los servicios

Ejecutar desde el directorio raíz:

```bash
docker compose up -d
```

## Cerrar los servicios y eliminar la BD

Ejecutar desde el directorio raíz:

```bash
docker compose down -v
```

## Estructura del proyecto

En el directorio raíz se encuentran el archivo de configuración de servicios de docker, `docker-compose.yml`, el archivo de variables de entorno, `.env`, y el directorio `docker-services` que contiene el archivo `Dockerfile` para construir la imagen del servicio de php, y los archivos para definir y popular la base de datos.

### Archivo `.env`

Configura variables de entorno que se utilizan en el archivo `docker-compose.yml`.

Define las siguientes variables:

 - `GRUPO`: ruta relativa desde el directorio raíz al directorio del proyecto del grupo
 - `APP_PORT`: puerto sobre el que se accederá a la aplicación
 - `DBADMIN_PORT`: puerto sobre el que se accederá a phpmyadmin
 - `DB_INIT`: ruta relativa desde el directorio raíz al directorio que contiene los archivos `.sql` para crear y popular la base de datos. El orden en que se cargan los archivos está definido según el orden alfabético del nombre de los archivos

### Archivo `docker-compose.yml`

Define 4 servicios:
 - `app`: monta el directorio definido por la variable `GRUPO` y sirve la aplicación en el puerto definido en `APP_PORT`
 - `composer`: monta el directorio definido por la variable `GRUPO` y, si existe un archivo `composer.json`, instala las depencias definidas en éste
 - `db`: monta el directorio definido por la variable `DB_INIT` y sirve el servidor de base de datos, ejecutando los archivos `.sql` en ese directorio
 - `dbadmin`: sirve una instancia de phpmyadmin en el puerto definido en `DBADMIN_PORT`

### Archivo `docker-services/app/Dockerfile`

Utilizando como base la imagen `php:8-fpm-alpine`, instala y/o habilita las extensiones `mysql`, `pdo`, y `pdo_mysql`, y define la configuración de desarrollo de php.

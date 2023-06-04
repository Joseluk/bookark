# BookArk
Este es un proyecto que usa Symfony, WebScraping y Docker. El proyecto se encuentra listo para ser desplegado tanto en entornos locales como en Google Cloud.

## Requisitos

- Docker
- Docker Compose
- git

## Ejecutar Localmente

Sigue estos pasos para desplegar la aplicación de forma local:

1. Clona el repositorio en tu máquina local:

    ```
    git clone https://github.com/Joseluk/bookark.git
    ```

2. Accede a la carpeta del proyecto:

    ```
    cd bookark
    ```

3. Ejecuta `docker-compose up` para construir y levantar los contenedores de Docker:

    ```
    docker-compose up
    ```

4. La aplicación ahora debería estar disponible en `localhost:8000` (o el puerto que hayas configurado).

Recuerda que puedes detener los contenedores y liberar los recursos utilizando `docker-compose down` en la misma carpeta donde ejecutaste `docker-compose up`.

## Deployar en Google Cloud

Para desplegar el proyecto en Google Cloud, puedes usar el botón a continuación:

[![Run on Google Cloud](https://storage.googleapis.com/cloudrun/button.svg)](https://console.cloud.google.com/cloudshell/editor?shellonly=true&cloudshell_image=gcr.io/cloudrun/button&cloudshell_git_repo=https://github.com/Joseluk/bookark.git)

Siguiendo este enlace, Google Cloud Shell abrirá una consola de terminal en tu navegador con el código del proyecto ya clonado. Deberás seguir las instrucciones en pantalla para desplegar la aplicación en Google Cloud Run.

Recuerda que necesitarás una cuenta de Google Cloud para desplegar la aplicación y puede haber costos asociados con el uso de los recursos de Google Cloud.

---

Si tienes alguna pregunta o problema con la instalación o el despliegue, no dudes en abrir un 'Issue' en el repositorio del proyecto.

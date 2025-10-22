# Proyecto admon

## Requisitos
- php
- node
- npm
- API key de Google (solicitar al equipo)

## Ejecutar
1. Instala dependencias
```
npm install
```

2. Escribir variables de entorno

Crea un archivo de nombre ".env" en la raiz del proyecto con el siguiente contenido:
```
GOOGLE_API_KEY=<AQUI ESCRIBE LA CLAVE QUE TE PROPORCIONO TU EQUIPO>
```

3. Iniciar el server de express js

Para ejecutar el server debes de volver a establecer la clave de google desde la terminal.
```
# Si estas en cmd
set GOOGLE_API_KEY=<AIzaSyBwQA0jC2uioHW3kl5-X1lgFoCGLhcIT1I>

# Si estas en powershell
$env:GOOGLE_API_KEY = "AIzaSyBwQA0jC2uioHW3kl5-X1lgFoCGLhcIT1I"

# Ejecutar el server
node src/js/ai/agent_server_http.mjs
```

4. Ejecutar app con PHP

```
php -S localhost:8000
```

## Detalles

- En el puerto 8000 corre la aplicacion con PHP
- En el puerto 3000 corre el server de express para el agente de ia
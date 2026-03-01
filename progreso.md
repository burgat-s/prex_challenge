# 🚀 API REST GIPHY - Progreso de Desarrollo

Este documento detalla el progreso y la configuración inicial de la API
REST construida para integrarse con GIPHY, cumpliendo con los requisitos
de utilizar PHP v8.3 o superior, Laravel Framework v11 o superior y
Docker.

------------------------------------------------------------------------

## 🛠️ 1. Entorno y Configuración Base (Docker)

El proyecto se inicializó utilizando **Laravel Sail** sobre WSL2, lo que
provee un entorno Dockerizado completo con PHP 8.4 y MariaDB/MySQL.

### Comandos iniciales

``` bash
# Creación del proyecto
curl -s "https://laravel.build/api-giphy-challenge?with=mysql" | bash

# Levantar contenedores
./vendor/bin/sail up -d
```

------------------------------------------------------------------------

## 🔐 2. Autenticación OAuth 2.0 (Laravel Passport)

Se implementó Laravel Passport para cumplir con el requisito de
autenticación OAuth2.0.

### Configuración de expiración de token

Se configuró globalmente la expiración del token a 30 minutos en:

`app/Providers/AppServiceProvider.php`

``` php
public function boot(): void
{
    Passport::tokensExpireIn(now()->addMinutes(30));
    Passport::personalAccessTokensExpireIn(now()->addMinutes(30));
}
```

------------------------------------------------------------------------

## 📦 3. Estandarización de Respuestas y Peticiones (DRY)

Para mantener código limpio y cumplir con principios de diseño (DRY), se
crearon clases base para estandarizar entradas y salidas.

### DTO de Respuesta

`app/Http/Responses/ApiResponse.php`\
Clase helper para devolver siempre la misma estructura JSON tanto en
casos de éxito como en errores.

### Base API Request

`app/Http/Requests/BaseApiRequest.php`\
Clase abstracta que sobrescribe el manejo de errores de validación de
Laravel para inyectar nuestro `ApiResponse`.

------------------------------------------------------------------------

## 🚪 4. Endpoint de Login

Se desarrolló el servicio de Login separando validación de lógica de
negocio.

-   **Request:** `LoginRequest` valida email y password.
-   **Controlador:** `AuthController` autentica y devuelve el token.

------------------------------------------------------------------------

## 🌍 5. Localización (Idioma Español)

Se instaló el paquete `laravel-lang/common` y se configuró:

    APP_LOCALE=es

Todos los mensajes de validación se devuelven nativamente en español.

------------------------------------------------------------------------

## 🌱 6. Database Seeder

Se configuró `DatabaseSeeder.php` para automatizar la creación de
usuarios de prueba según el entorno, facilitando pruebas locales.

------------------------------------------------------------------------

# 📝 NUEVOS AVANCES

## 🕵️‍♂️ 7. Middleware de Auditoría

Para cumplir con el requerimiento de persistir toda interacción con los
servicios:

-   Modelo: `ApiLog`
-   Middleware: `AuditApiRequest`

### Optimización de Rendimiento (Terminable Middleware)

Se utiliza el método `terminate()` para registrar datos en segundo plano
luego de enviar la respuesta.

### Información registrada

-   Usuario que realizó la petición\
-   Servicio consultado\
-   Cuerpo de la petición (ocultando contraseñas)\
-   Código HTTP de la respuesta\
-   Cuerpo de la respuesta\
-   IP de origen

------------------------------------------------------------------------

## 🧩 8. Integración GIPHY y Arquitectura SOLID

Se construyó la integración aplicando el Principio de Inversión de
Dependencias.

### Contrato

`app/Contracts/GifProviderInterface.php`\
Define los métodos `search` y `findById`.

### Servicio

`app/Services/GiphyService.php`\
Implementa el contrato utilizando el HTTP Client de Laravel.

### Service Provider

Se enlazó la interfaz con la implementación en el contenedor de
dependencias, permitiendo reemplazar el proveedor en el futuro sin
modificar controladores.

------------------------------------------------------------------------

## 🔍 9. Endpoints de Búsqueda de GIFs

Protegidos con middleware de autenticación Passport.

### Buscar GIFs

`GET /api/gifs/search`

-   QUERY (requerido)
-   LIMIT (opcional)
-   OFFSET (opcional)

Devuelve la colección de resultados.

### Buscar GIF por ID

`GET /api/gifs/{id}`

Acepta IDs alfanuméricos (según API real de GIPHY).

------------------------------------------------------------------------

## ⭐ 10. Endpoint de Guardar Favorito

`POST /api/favorites`

Permite almacenar un GIF favorito para un usuario.

### Seguridad e Integridad

-   Validación de entradas: GIF_ID, ALIAS, USER_ID.
-   Prevención IDOR: Se verifica que el USER_ID coincida con el usuario
    autenticado.
-   Escalabilidad: Columna `provider` en favoritos para evitar
    colisiones futuras.
-   Consistencia ACID: Uso de `DB::transaction()` para garantizar
    integridad ante fallos.

------------------------------------------------------------------------

# ✅ Estado Actual

La API se encuentra:

-   Dockerizada
-   Autenticada con OAuth2 (Passport)
-   Con arquitectura desacoplada (SOLID)
-   Con auditoría persistente
-   Con endpoints funcionales de búsqueda y favoritos
-   Preparada para escalar a múltiples proveedores de GIFs

------------------------------------------------------------------------

**Proyecto listo para pruebas técnicas y extensiones futuras.**

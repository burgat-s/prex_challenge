# Diagrama de Secuencia

Este diagrama detalla el flujo de interacciones entre los componentes del sistema para los casos de uso principales.

El siguiente código puede ser copiado y pegado en el editor online [Mermaid.live](https://mermaid.live/) para su visualización y modificación.

```mermaid
sequenceDiagram
    autonumber
    actor Cliente as App / Postman
    participant API as Laravel (Controladores)
    participant DB as MySQL (Passport / Data)
    participant Giphy as API Externa (GIPHY)

    %% BLOQUE 1: LOGIN Y OBTENCIÓN DE TOKEN
    rect rgb(240, 248, 255)
        Note over Cliente, DB: UC1: Login de Usuario (OAuth 2.0)
        Cliente->>API: POST /api/login (email, password)
        API->>DB: Verifica Credenciales
        DB-->>API: Usuario Válido
        API->>DB: Passport genera JWT Stateless
        API-->>Cliente: 200 OK (Retorna Access Token)
        API-)+DB: [Async] Middleware Audit: Persiste Log
    end

    %% BLOQUE 2: BÚSQUEDA DE GIFS (CON GIPHY)
    rect rgb(245, 245, 245)
        Note over Cliente, Giphy: UC2 & UC3: Búsqueda (Término / ID)
        Cliente->>API: GET /api/gifs/... + Header(Bearer Token)
        API->>DB: Passport Middleware valida Token
        DB-->>API: Token OK (Autenticado)
        API->>Giphy: HTTP GET api.giphy.com/... (Inyecta API_KEY)
        Giphy-->>API: Retorna JSON Original
        API->>API: Mapea y Estandariza DTO
        API-->>Cliente: 200 OK (Colección/Item)
        API-)+DB: [Async] Middleware Audit: Persiste Log
    end

    %% BLOQUE 3: GESTIÓN DE FAVORITOS (LOCAL)
    rect rgb(240, 255, 240)
        Note over Cliente, DB: UC4 & UC6: Guardar / Listar Favoritos
        Cliente->>API: POST/GET /api/favorites + Header(Bearer Token)
        API->>DB: Passport Middleware valida Token
        DB-->>API: Token OK (Autenticado)
        API->>DB: DB::transaction (Inserta / Consulta Favoritos)
        DB-->>API: Operación Exitosa
        API-->>Cliente: 200/201 OK (JSON Estandarizado)
        API-)+DB: [Async] Middleware Audit: Persiste Log
    end
```

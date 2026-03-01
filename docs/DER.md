# Diagrama de Entidad-Relación (DER)

Este diagrama representa el modelo de datos relacional de la API, incluyendo las tablas de negocio, la auditoría y la gestión de tokens de OAuth2 (Passport).

El siguiente código puede ser copiado y pegado en el editor online [Mermaid.live](https://mermaid.live/) para su visualización y modificación.

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        timestamp created_at
        timestamp updated_at
    }

    FAVORITES {
        bigint id PK
        bigint user_id FK
        string gif_id
        string alias
        timestamp created_at
        timestamp updated_at
    }

    API_LOGS {
        bigint id PK
        bigint user_id FK "nullable"
        string service
        json request_body
        int response_code
        json response_body
        string ip_address
        timestamp created_at
        timestamp updated_at
    }

    OAUTH_ACCESS_TOKENS {
        string id PK
        bigint user_id FK
        bigint client_id FK
        text scopes
        boolean revoked
        datetime expires_at
        datetime created_at
        datetime updated_at
    }

    OAUTH_CLIENTS {
        bigint id PK
        bigint user_id FK "nullable"
        string name
        string secret
        string provider
        string redirect
        boolean personal_access_client
        boolean password_client
        boolean revoked
        timestamp created_at
        timestamp updated_at
    }

    %% Relaciones
    USERS ||--o{ FAVORITES : "tiene"
    USERS ||--o{ API_LOGS : "genera"
    USERS ||--o{ OAUTH_ACCESS_TOKENS : "posee"
    USERS ||--o{ OAUTH_CLIENTS : "crea"
    OAUTH_CLIENTS ||--o{ OAUTH_ACCESS_TOKENS : "emite"
```

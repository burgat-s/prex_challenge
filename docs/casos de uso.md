# Casos de Uso

Este diagrama ilustra los principales casos de uso de la API, los actores involucrados y las interacciones con sistemas externos.

El siguiente código puede ser copiado y pegado en el editor online [Mermaid.live](https://mermaid.live/) para su visualización y modificación.

```mermaid
flowchart LR
    %% Actores
    Guest(("Usuario<br>No Autenticado"))
    AuthUser(("Usuario<br>Autenticado"))
    Giphy((("API Externa<br>GIPHY")))

    %% Límites del Sistema (Nuestra API)
    subgraph Sistema API REST Prex
        direction TB
        UC1(["Login / Obtener Token"])
        UC2(["Buscar GIFs por Término"])
        UC3(["Buscar GIF por ID"])
        UC4(["Guardar GIF Favorito"])
        UC6(["Listar GIFs Favoritos"])
        UC5(["Registrar Auditoría"])
    end

    %% Relaciones de Usuarios
    Guest -->|Envía Credenciales| UC1
    AuthUser -->|Envía Token JWT| UC2
    AuthUser -->|Envía Token JWT| UC3
    AuthUser -->|Envía Token JWT| UC4
    AuthUser -->|Envía Token JWT| UC6

    %% Relación de Auditoría (Se ejecuta siempre)
    UC1 -.->|<< include >>| UC5
    UC2 -.->|<< include >>| UC5
    UC3 -.->|<< include >>| UC5
    UC4 -.->|<< include >>| UC5
    UC6 -.->|<< include >>| UC5

    %% Relaciones con Sistemas Externos
    UC2 ===>|Consume| Giphy
    UC3 ===>|Consume| Giphy
```

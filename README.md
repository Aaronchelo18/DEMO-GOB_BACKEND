# DEMO-GOB Backend

Backend API para el demo SISCAT.

## Levantar con Docker

```powershell
docker compose up --build
```

URL:

```text
http://127.0.0.1:8081
```

## Levantar con PHP local

```powershell
.\INICIAR_BACKEND.bat
```

## Endpoints

```text
GET  /api/health
GET  /api/session
GET  /api/catalog
GET  /api/services
GET  /api/services/{servicio}/groups/{grupo}/items
GET  /api/items/{item}
POST /api/captures
GET  /api/captures
```

`POST /api/captures` acepta JSON o formulario `multipart/form-data`.

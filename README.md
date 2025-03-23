# scriptorium

# passo 1 - adicione o arquivo .env no diretorio raiz do projeto

```
APP_NAME=scriptorium
APP_ENV=dev

DB_DRIVER=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=scriptorium
DB_USERNAME=scripto
DB_PASSWORD=scripto142536
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
DB_PREFIX=

REDIS_HOST=cache
REDIS_AUTH=null
REDIS_PORT=6379
REDIS_DB=0
```

# passo 2 - execute o comando para subir a aplicação backend/frontend

```
docker-compose -f docker-compose-app.yml up -d --remove-orphans
```
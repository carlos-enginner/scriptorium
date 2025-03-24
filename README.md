# Scriptorium

# Configurando a aplicação

## passo 1 - adicione o arquivo .env no diretorio raiz do projeto

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

# Subindo a aplicação
```
docker-compose -f docker-compose-app.yml -f docker-compose-devops.yaml up -d --remove-orphans
```

# monitorando a aplicação

# para acessar o sonarqube da aplicação 
```
https://sonarcloud.io/organizations/carlos-vargas/projects
```

# passa acessar o graylog
http://localhost:9000/streams/
- clicar em app.scriptorium
admin
142536
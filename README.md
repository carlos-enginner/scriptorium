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

GRAYLOG_IP=graylog
GRAYLOG_STREAM_ID=app.scriptorium
GRAYLOG_TCP_PORT=12201
GRAYLOG_UDP_PORT=12201
```

# Subindo a aplicação
![Containers](imagens/containers.png)
docker-compose -f docker-compose-app.yml -d --remove-orphans

# para acessar o aplicação
![Application](imagens/app.png)
http://localhost:3000/

# para acessar a documentação (swagger) da api
![Swagger UI](imagens/swagger.png)
http://localhost:9500/docs

# monitorando a aplicação

# para acessar o graylog
![GELF](imagens/graylog.png)

http://localhost:9000/streams/
- clicar em app.scriptorium
admin
142536

# para acessar o sonarqube do projeto
![GELF](imagens/sonarqube.png)
https://sonarcloud.io/organizations/carlos-vargas/projects

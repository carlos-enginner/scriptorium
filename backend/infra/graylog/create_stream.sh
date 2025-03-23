#!/bin/bash

until curl -s http://localhost:9000/api/system/cluster/node | grep -q '"node_id"'; do
    echo "Aguardando Graylog iniciar..."
    sleep 10
done

curl -X POST "http://localhost:9000/api/streams" \
    -H "Content-Type: application/json" \
    -u admin: \
    '{
          "title": "scriptorium",
          "description": "Stream para o Scriptorium",
          "matching_type": "AND",
          "creator_user_id": "admin"
        }' <SUA_SENHA_DO_GRAYLOG >-d

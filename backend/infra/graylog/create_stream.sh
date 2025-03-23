#!/bin/bash

until curl -s -u admin:142536 GET http://localhost:9000/api/system/cluster/node | grep -q '"node_id"'; do
    sleep 10
done

DIR_PATH="/usr/share/graylog/data"
FILE_SUFFIX="app.scriptorium.stream."

if ! ls "$DIR_PATH/$FILE_SUFFIX"* 1>/dev/null 2>&1; then
    # cria
    STREAM_ID=$(curl -s -u admin:142536 -X POST "http://localhost:9000/api/streams" \
        -H "Content-Type: application/json" \
        -H "X-Requested-By: cli" \
        -d "$(curl -s -u admin:142536 -X GET "http://localhost:9000/api/system/indices/index_sets" \
            -H "X-Requested-By: cli" -H "Accept: application/json" |
            grep -o '"id":"[^"]*"' | head -n 1 | sed -E 's/"id":"([^"]*)"/{ "title": "app.scriptorium", "description": "Stream para o Scriptorium", "matching_type": "AND", "index_set_id": "\1", "remove_matches_from_default_stream": false, "rules": [] }/')")

    # extrai
    STREAM_ID=$(curl -s -u admin:142536 -X GET "http://localhost:9000/api/streams" \
        -H "X-Requested-By: cli" |
        grep -o '"id":"[^"]*"\|"title":"[^"]*"' |
        paste - - |
        grep '"title":"app.scriptorium"' |
        awk -F ':"' '{print $2}' |
        sed 's/"//g' | sed 's/title//g' | sed 's/^[ \t]*//;s/[ \t]*$//')

    # salva
    echo "$STREAM_ID" >"/usr/share/graylog/data/app.scriptorium.stream.${STREAM_ID}"

    # ativa
    curl -s -u admin:142536 -X POST "http://localhost:9000/api/streams/${STREAM_ID}/resume" \
        -H "X-Requested-By: cli"

    echo "Done"
fi


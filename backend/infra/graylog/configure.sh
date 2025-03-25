#!/bin/bash

until curl -s -u admin:142536 GET http://localhost:9000/api/system/cluster/node | grep -q '"node_id"'; do
    sleep 10
done

DIR_PATH="/usr/share/graylog/data"
FILE_SUFFIX="app.scriptorium.stream."

if ! ls "$DIR_PATH/$FILE_SUFFIX"* 1>/dev/null 2>&1; then
    # Ativa TCP/UDP
    curl -s -u admin:142536 -X POST "http://localhost:9000/api/system/inputs" \
    -H "Content-Type: application/json" \
    -H "X-Requested-By: cli" \
    -d '{
        "title": "GELF TCP",
        "type": "org.graylog2.inputs.gelf.tcp.GELFTCPInput",
        "global": true,
        "configuration": {
            "bind_address": "0.0.0.0",
            "charset_name": "UTF-8",
            "decompress_size_limit": 8388608,
            "max_message_size": 2097152,
            "number_worker_threads": 4,
            "override_source": "",
            "port": 12201,
            "recv_buffer_size": 1048576,
            "tcp_keepalive": false,
            "tls_cert_file": "",
            "tls_client_auth": "disabled",
            "tls_client_auth_cert_file": "",
            "tls_enable": false,
            "tls_key_file": "",
            "tls_key_password": "********",
            "use_null_delimiter": true
        }
    }'

    curl -s -u admin:142536 -X POST "http://localhost:9000/api/system/inputs" \
    -H "Content-Type: application/json" \
    -H "X-Requested-By: cli" \
    -d '{
        "title": "GELF UDP",
        "type": "org.graylog2.inputs.gelf.udp.GELFUDPInput",
        "global": true,
        "configuration": {
            "bind_address": "0.0.0.0",
            "charset_name": "UTF-8",
            "decompress_size_limit": 8388608,
            "number_worker_threads": 4,
            "override_source": "",
            "port": 12201,
            "recv_buffer_size": 262144
        }
    }'

    echo "Done"
fi
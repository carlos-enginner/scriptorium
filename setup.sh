#!/bin/sh
git config core.hooksPath hooks

docker exec scriptorium_app php bin/hyperf.php migrate
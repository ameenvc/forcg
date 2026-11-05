#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
# Legacy compatibility runtime; not a production deployment image.
image='webdevops/php:7.4@sha256:17e88eadbe9072ed80d6e30e0e2e25f3e7a3ac13072435556aff9d456e706b4d'
cache_dir="${CGBOD_COMPOSER_CACHE:-/workspace/.cache/cgbod-composer}"
mkdir -p "$cache_dir"
docker network inspect cgbod-dev >/dev/null 2>&1 || docker network create cgbod-dev >/dev/null
common=(--rm --network cgbod-dev --user "$(id -u):$(id -g)" -w /app
    --mount "type=bind,src=$PWD,dst=/app"
    --mount "type=bind,src=/etc/ssl/certs/ca-certificates.crt,dst=/run/ca.pem,readonly"
    -e SSL_CERT_FILE=/run/ca.pem -e GIT_SSL_CAINFO=/run/ca.pem)
case "${1:-test}" in
    install)
        docker run "${common[@]}" --entrypoint composer \
            --mount "type=bind,src=$cache_dir,dst=/tmp/composer" \
            -e COMPOSER_HOME=/tmp/composer -e COMPOSER_CAFILE=/run/ca.pem \
            "$image" install --no-interaction --prefer-source --no-scripts --no-plugins
        ;;
    test)
        docker run "${common[@]}" --entrypoint php "$image" vendor/bin/phpunit --filter RiskAi
        ;;
    serve)
        docker run "${common[@]}" -p 127.0.0.1:8080:8080 --entrypoint php "$image" \
            -d curl.cainfo=/run/ca.pem -d openssl.cafile=/run/ca.pem -S 0.0.0.0:8080 -t public server.php
        ;;
    *) echo 'Usage: bash tools/risk-ai-dev.sh {install|test|serve}' >&2; exit 2 ;;
esac

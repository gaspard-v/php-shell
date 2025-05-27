#!/bin/bash

set -eEuo pipefail

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
NGINX_WEB_DIR="/usr/share/nginx/html"
NGINX_BAK_WEB_DIR="/usr/share/nginx/html.bak"
PHP_DIRECTORY="/usr/local/php/current"

if [ "$(id -u)" -ne 0 ]; then
    echo -e 'Script must be run as root. Use sudo, su, or add "USER root" to your Dockerfile before running this script.'
    exit 1
fi

manage_nginx_files() {
    if [ ! -d "$NGINX_WEB_DIR" ]; then
        printf 2>&1 "Folder $NGINX_WEB_DIR does not exist.\n"
        return 1
    fi

    if [ -L "$NGINX_WEB_DIR" ]; then
        printf "$NGINX_WEB_DIR is already a symbolic link.\n"
        return
    fi

    printf "Moving $NGINX_WEB_DIR directory to $NGINX_BAK_WEB_DIR.\n"
    mv -v "$NGINX_WEB_DIR" "$NGINX_BAK_WEB_DIR"

    printf "Creating symlink $SCRIPT_DIR -> $NGINX_WEB_DIR\n"
    ln -sv "$SCRIPT_DIR" "$NGINX_WEB_DIR"
}

fix_php_fpm_path_bug() {
    local php_fpm_sbin=""
    local php_fpm_bin=""

    if type "php-fpm" > /dev/null; then
        printf "php-fpm is already in path.\n"
        return
    fi

    php_fpm_sbin="$PHP_DIRECTORY/sbin/php-fpm"
    php_fpm_bin="$PHP_DIRECTORY/bin/php-fpm"

    if [ -f "$php_fpm_bin" ]; then
        printf "php-fpm is already in $php_fpm_bin.\n"
        return
    fi

    if [ ! -f "$php_fpm_sbin" ]; then
        printf 2>&1 "$php_fpm_sbin does not exist.\n"
        return 1
    fi

    ln -sv "$php_fpm_sbin" "$php_fpm_bin"

}

restart_nginx_php_fpm() {
    local p=0

    printf "Restarting nginx and php-fpm.\n"
    killall --verbose "nginx" || true
    killall --verbose "php-fpm" || true

    php-fpm & p=$!
    printf "php-fpm launched with pid ${p}.\n"
    nginx & p=$!
    printf "nginx launched with pid ${p}.\n"
}

manage_nginx_files
fix_php_fpm_path_bug
restart_nginx_php_fpm

#!/bin/bash
# ─────────────────────────────────────────────────────────────────
# Reinventa Landing — Deploy en VPS que YA tiene Franky + EuforIA
# Nginx, MySQL, PHP 8.2 ya están instalados.
# Expone http://5.78.198.154/reinventa vía proxy a 127.0.0.1:8082
# Corre ONCE como root.
# ─────────────────────────────────────────────────────────────────
set -e

APP_DIR="/var/www/reinventa"
REPO_URL="https://github.com/luisreinventa/Encuadre-Financiero.git"
DB_NAME="reinventa_landing"
DB_USER="reinventa_user"
DB_PASS=$(openssl rand -base64 24 | tr -d '/+=' | head -c 24)
APP_HOST=$(hostname -I | awk '{print $1}')
APP_URL="http://${APP_HOST}/reinventa"
APP_PATH_PREFIX="reinventa"
FRANKY_IP_CONF="/etc/nginx/sites-enabled/franky-ip.conf"

echo "═══════════════════════════════════════════"
echo "  Reinventa Landing — Subpath /reinventa"
echo "  URL: ${APP_URL}"
echo "═══════════════════════════════════════════"

# ── 0. Pre-flight ─────────────────────────────────────────────────
[ "$EUID" -ne 0 ] && { echo "✗ Corre como root"; exit 1; }
[ ! -f "$FRANKY_IP_CONF" ] && { echo "✗ No existe $FRANKY_IP_CONF"; exit 1; }
command -v php8.3 >/dev/null   || { echo "✗ PHP 8.3 no instalado (apt install php8.3-fpm php8.3-cli ...)"; exit 1; }
command -v composer >/dev/null || { echo "✗ Composer no instalado"; exit 1; }
command -v mysql >/dev/null    || { echo "✗ MySQL no instalado"; exit 1; }
export COMPOSER_ALLOW_SUPERUSER=1

# ── 1. MySQL ──────────────────────────────────────────────────────
mysql -u root <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
echo "✅ DB '${DB_NAME}' lista (usuario: ${DB_USER})"

# ── 2. Clone repo ─────────────────────────────────────────────────
if [ -d "$APP_DIR/.git" ]; then
    echo "⚠️  $APP_DIR existe — git pull"
    cd "$APP_DIR" && git pull
else
    git clone "$REPO_URL" "$APP_DIR"
    cd "$APP_DIR"
fi

# ── 3. Composer ───────────────────────────────────────────────────
php8.3 /usr/bin/composer install --no-dev --optimize-autoloader --no-interaction
echo "✅ Dependencias instaladas"

# ── 4. .env ───────────────────────────────────────────────────────
cp .env.example .env

set_env() {
    local key=$1 value=$2
    if grep -q "^${key}=" .env; then
        sed -i "s|^${key}=.*|${key}=${value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

set_env "APP_URL" "${APP_URL}"
set_env "APP_PATH_PREFIX" "${APP_PATH_PREFIX}"
set_env "APP_ENV" "production"
set_env "APP_DEBUG" "false"
set_env "DB_CONNECTION" "mysql"
set_env "DB_HOST" "127.0.0.1"
set_env "DB_PORT" "3306"
set_env "DB_DATABASE" "${DB_NAME}"
set_env "DB_USERNAME" "${DB_USER}"
set_env "DB_PASSWORD" "${DB_PASS}"
set_env "QUEUE_CONNECTION" "database"
grep -q "^GHL_WEBHOOK_URL=" .env || echo "GHL_WEBHOOK_URL=" >> .env

php8.3 artisan key:generate --force
echo "✅ .env configurado y APP_KEY generado"

# ── 5. Migraciones (incluida jobs table para queue:database) ──────
php8.3 artisan queue:table 2>/dev/null || true
php8.3 artisan migrate --force
echo "✅ Migraciones ejecutadas"

# ── 6. Permisos ───────────────────────────────────────────────────
chown -R www-data:www-data "$APP_DIR"
chmod -R 755 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
php8.3 artisan storage:link || true
echo "✅ Permisos OK"

# ── 7. Cache de producción ────────────────────────────────────────
php8.3 artisan config:cache
php8.3 artisan route:cache
php8.3 artisan view:cache
echo "✅ Cache optimizado"

# ── 8. Nginx server interno (127.0.0.1:8082) ──────────────────────
cp "$APP_DIR/deploy/nginx-reinventa.conf" /etc/nginx/sites-available/reinventa
ln -sf /etc/nginx/sites-available/reinventa /etc/nginx/sites-enabled/reinventa
echo "✅ sites-enabled/reinventa instalado"

# ── 9. Patch franky-ip.conf (idempotente, con backup) ─────────────
if grep -q "Reinventa landing" "$FRANKY_IP_CONF"; then
    echo "ℹ️  Patch ya aplicado en franky-ip.conf, skip"
else
    BACKUP="${FRANKY_IP_CONF}.bak.$(date +%Y%m%d%H%M%S)"
    cp "$FRANKY_IP_CONF" "$BACKUP"
    echo "📦 Backup: $BACKUP"

    PATCH=$(cat "$APP_DIR/deploy/franky-ip-patch.conf")
    awk -v patch="$PATCH" '
        { lines[NR]=$0; if ($0 ~ /^\}$/) last=NR }
        END {
            for (i=1; i<=NR; i++) {
                if (i == last) print patch
                print lines[i]
            }
        }
    ' "$BACKUP" > "$FRANKY_IP_CONF"

    if ! nginx -t 2>&1; then
        echo "✗ nginx -t falló — restaurando backup"
        cp "$BACKUP" "$FRANKY_IP_CONF"
        exit 1
    fi
fi

# ── 10. Reload ────────────────────────────────────────────────────
nginx -t && systemctl reload nginx
echo "✅ Nginx recargado"

# ── 11. Guardar credenciales ──────────────────────────────────────
echo "$DB_PASS" > /root/reinventa_db_pass.txt
chmod 600 /root/reinventa_db_pass.txt

echo ""
echo "═══════════════════════════════════════════"
echo "  ✅ Reinventa desplegado"
echo "  🌐 ${APP_URL}"
echo "  🗄️  DB pass: /root/reinventa_db_pass.txt"
echo "═══════════════════════════════════════════"
echo ""
echo "⚠️  PENDIENTE:"
echo "  1. Edita ${APP_DIR}/.env y pega GHL_WEBHOOK_URL=..."
echo "  2. Reaplica cache:  cd ${APP_DIR} && php8.3 artisan config:cache"
echo "  3. (Opcional) Worker queue:database:"
echo "     systemd unit en deploy/reinventa-worker.service"
echo ""
echo "Franky y EuforIA siguen intactos:"
echo "  http://${APP_HOST}/        → Franky"
echo "  https://euforiaprogram.com → EuforIA"
echo "  http://${APP_HOST}/reinventa → Reinventa"

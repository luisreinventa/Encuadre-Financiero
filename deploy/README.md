# Deploy Reinventa al VPS Hetzner (5.78.198.154)

Coexiste con Franky_Agent, franky-voz y EuforIA sin tocar `franky-ip.conf` más
que para agregar el `location /reinventa { proxy_pass ... }`.

## Arquitectura

```
http://5.78.198.154/reinventa  → nginx (franky-ip.conf, :80)
                                 → proxy_pass http://127.0.0.1:8082
                                   → nginx server interno (sites-enabled/reinventa)
                                     → php-fpm 8.2 (/var/www/reinventa/public)
```

## Despliegue inicial

Desde tu máquina, conectado por SSH al VPS como root:

```bash
git clone https://github.com/luisreinventa/Encuadre-Financiero.git /tmp/reinventa-bootstrap
cd /tmp/reinventa-bootstrap/deploy
chmod +x deploy-same-vps.sh
./deploy-same-vps.sh
```

El script:

1. Crea DB MySQL `reinventa_landing` con usuario `reinventa_user` y password aleatorio
2. Clona el repo en `/var/www/reinventa`
3. `composer install --no-dev` + `.env` configurado para subpath
4. `php artisan key:generate`, `migrate`, `config:cache`, `route:cache`, `view:cache`
5. Instala `nginx-reinventa.conf` como sites-enabled/reinventa (escucha 127.0.0.1:8082)
6. Hace **backup** de `franky-ip.conf` e inserta `location /reinventa { proxy_pass ... }`
   antes del último `}` del server block. Si `nginx -t` falla, restaura el backup.

## Post-despliegue

```bash
# 1. Pega tu webhook GHL
nano /var/www/reinventa/.env
# GHL_WEBHOOK_URL=https://services.leadconnectorhq.com/hooks/TU-ID

# 2. Refresca cache
cd /var/www/reinventa && php artisan config:cache

# 3. Worker queue (opcional pero recomendado)
cp deploy/reinventa-worker.service /etc/systemd/system/
systemctl daemon-reload
systemctl enable --now reinventa-worker
systemctl status reinventa-worker
```

## Actualizar después del primer deploy

```bash
cd /var/www/reinventa
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
systemctl restart reinventa-worker
```

## Rollback del patch de franky-ip.conf

Si algo se rompe:

```bash
ls /etc/nginx/sites-enabled/franky-ip.conf.bak.*
cp /etc/nginx/sites-enabled/franky-ip.conf.bak.YYYYMMDDHHMMSS /etc/nginx/sites-enabled/franky-ip.conf
rm /etc/nginx/sites-enabled/reinventa
nginx -t && systemctl reload nginx
```

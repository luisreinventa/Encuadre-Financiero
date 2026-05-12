# Reinventa Landing · Laravel

## Arrancar

1. Edita `.env` y pega tu webhook:
   `GHL_WEBHOOK_URL=https://services.leadconnectorhq.com/hooks/TU-ID`

2. Levanta el servidor: `php artisan serve`

3. En otra terminal, levanta el worker: `php artisan queue:work`

4. Abre http://localhost:8000

## Reenviar leads que fallaron

```bash
php artisan tinker
>>> App\Models\Lead::whereNull('ghl_sent_at')->get()->each(fn($l) => App\Jobs\SendLeadToGoHighLevel::dispatch($l));
```

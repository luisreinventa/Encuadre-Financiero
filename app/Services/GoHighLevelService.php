<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GoHighLevelService
{
    protected string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = (string) config('services.gohighlevel.webhook_url');

        if ($this->webhookUrl === '') {
            throw new RuntimeException('GHL_WEBHOOK_URL no está configurado en .env');
        }
    }

    public function sendLead(Lead $lead): array
    {
        $payload = [
            'first_name'      => $this->firstName($lead->name),
            'last_name'       => $this->lastName($lead->name),
            'full_name'       => $lead->name,
            'email'           => $lead->email,
            'phone'           => $lead->phone,
            'plan'            => $lead->plan,
            'plan_label'      => $lead->planLabel(),
            'plan_amount_mxn' => $lead->planAmount(),
            'source'          => 'Landing Bootcamp Reinventa',
            'tags'            => ['bootcamp-reinventa', 'plan-' . $lead->plan],
            'submitted_at'    => $lead->created_at->toIso8601String(),
        ];

        $response = Http::timeout(15)
            ->retry(3, 1000, throw: false)
            ->acceptJson()
            ->asJson()
            ->post($this->webhookUrl, $payload);

        if (! $response->successful()) {
            Log::error('GHL webhook failed', [
                'lead_id' => $lead->id,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
            throw new RuntimeException('GHL returned status ' . $response->status());
        }

        return $response->json() ?? ['status' => 'ok'];
    }

    protected function firstName(string $fullName): string
    {
        return explode(' ', trim($fullName), 2)[0] ?? $fullName;
    }

    protected function lastName(string $fullName): string
    {
        $parts = explode(' ', trim($fullName), 2);
        return $parts[1] ?? '';
    }
}

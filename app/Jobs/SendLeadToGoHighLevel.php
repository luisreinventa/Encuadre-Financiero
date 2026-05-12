<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\GoHighLevelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendLeadToGoHighLevel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 30;
    public int $timeout = 30;

    public function __construct(public Lead $lead) {}

    public function handle(GoHighLevelService $ghl): void
    {
        $response = $ghl->sendLead($this->lead);

        $this->lead->update([
            'ghl_sent_at'  => now(),
            'ghl_response' => $response,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Lead sync to GHL permanently failed', [
            'lead_id' => $this->lead->id,
            'email'   => $this->lead->email,
            'error'   => $exception->getMessage(),
        ]);
    }
}

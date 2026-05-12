<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\SendLeadToGoHighLevel;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = Lead::create($request->validated());

        SendLeadToGoHighLevel::dispatch($lead);

        return response()->json([
            'success' => true,
            'message' => 'Te contactaremos en 24 hrs para confirmar tu lugar.',
            'lead_id' => $lead->id,
        ], 201);
    }
}

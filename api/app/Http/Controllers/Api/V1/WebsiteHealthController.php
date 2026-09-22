<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpsertWebsiteHealthRequest;
use App\Http\Resources\Api\V1\WebsiteHealthResource;
use App\Models\Website;
use Illuminate\Http\JsonResponse;

class WebsiteHealthController extends Controller
{
    public function show(Website $website): JsonResponse
    {
        $this->authorize('view', $website);

        $health = $website->health;

        if ($health === null) {
            abort(404);
        }

        return response()->json([
            'health' => WebsiteHealthResource::make($health)->resolve(),
        ]);
    }

    public function update(UpsertWebsiteHealthRequest $request, Website $website): JsonResponse
    {
        $health = $website->health()->updateOrCreate([], $request->validated());

        return response()->json([
            'health' => WebsiteHealthResource::make($health->refresh())->resolve(),
        ]);
    }
}

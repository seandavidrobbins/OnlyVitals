<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWebsiteRequest;
use App\Http\Requests\Api\V1\UpdateWebsiteRequest;
use App\Http\Resources\Api\V1\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebsiteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Website::class);

        return response()->json([
            'websites' => WebsiteResource::collection(
                $request->user()->websites()->latest()->get(),
            )->resolve(),
        ]);
    }

    public function store(StoreWebsiteRequest $request): JsonResponse
    {
        $website = $request->user()->websites()->create($request->validated());

        return response()->json([
            'website' => WebsiteResource::make($website)->resolve(),
        ], 201);
    }

    public function show(Website $website): JsonResponse
    {
        $this->authorize('view', $website);

        return response()->json([
            'website' => WebsiteResource::make($website)->resolve(),
        ]);
    }

    public function update(UpdateWebsiteRequest $request, Website $website): JsonResponse
    {
        $website->update($request->validated());

        return response()->json([
            'website' => WebsiteResource::make($website->refresh())->resolve(),
        ]);
    }

    public function destroy(Website $website): Response
    {
        $this->authorize('delete', $website);

        $website->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Http\Requests\StoreBugReportRequest;
use App\Http\Requests\UpdateBugReportRequest;
use App\Http\Resources\BugReportResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BugReportController extends Controller
{
    public function store(StoreBugReportRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        BugReport::create($validated);

        return new JsonResponse(['message' => ['success' => ['Bug report successfully created.']]], Response::HTTP_OK);
    }

    public function update(UpdateBugReportRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        
        BugReport::find($id)->update($validated);

        $return = BugReportResource::collection(BugReport::all());

    return new JsonResponse(['message' => ['success' => ["Bug Report updated."]], 'data' => $return], Response::HTTP_OK);
    }

    public function show($argument){
        if ($argument == 'all') {
            return BugReportResource::collection(BugReport::all());
        };
        return new JsonResponse(['message' => ['error' => ["Only bugreport/all is permitted."]]], Response::HTTP_OK);
    }
}

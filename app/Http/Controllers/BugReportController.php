<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Models\BugReport;
use App\Http\Requests\StoreBugReportRequest;
use App\Http\Requests\UpdateBugReportRequest;
use App\Http\Resources\BugReportResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BugReportController extends Controller
{
    public function store(StoreBugReportRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        BugReport::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_BUG_REPORT', 'Bug report stored: ' . $validated['title']);

        return new JsonResponse(['message' => ['success' => 'Bug report successfully created.']], Response::HTTP_OK);
    }

    public function update(UpdateBugReportRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        BugReport::find($id)->update($validated);

        $return = BugReportResource::collection(BugReport::all());
        ActionTrackingHandler::handleAction($request, 'UPDATE_BUG_REPORT', 'Bug report updated: ' . $validated['title']);

        return new JsonResponse(['message' => ['success' => "Bug Report updated."], 'data' => $return], Response::HTTP_OK);
    }
}

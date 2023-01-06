<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\NotificationHandler;
use App\Helpers\ResponseWrapper;
use App\Models\BugReport;
use App\Http\Requests\StoreBugReportRequest;
use App\Http\Requests\UpdateBugReportRequest;
use App\Http\Resources\BugReportResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class BugReportController extends Controller
{
    public function getBugReports() 
    {
        return BugReportResource::collection(BugReport::orderByDesc('created_at')->get());
    }

    public function store(StoreBugReportRequest $request): JsonResponse
    {
        $validated = $request->validated();
        if (Auth::check()) {
            $validated['user_id'] = Auth::user()->id;
        }

        BugReport::create($validated);
        ActionTrackingHandler::handleAction($request, 'STORE_BUG_REPORT', 'Bug report stored: ' . $validated['title']);

        return ResponseWrapper::successResponse(__('messages.bug.created'));
    }

    public function update(UpdateBugReportRequest $request, BugReport $bugReport): JsonResponse
    {
        $validated = $request->validated();

        $bugReport->update($validated);

        if ($bugReport->status === 3 && $bugReport->user_id && $request['sendMessageToReporter']) {
            NotificationHandler::create(
                $bugReport->user_id,
                __('messages.bug.resolved_title'),
                __('messages.bug.resolved_text', ['bug_title' => $bugReport->title])
            );
        }

        ActionTrackingHandler::handleAction($request, 'UPDATE_BUG_REPORT', 'Bug report updated: ' . $validated['title']);

        return ResponseWrapper::successResponse(__('messages.bug.updated'), 
            ['bugReports' => BugReportResource::collection(BugReport::orderByDesc('created_at')->get())]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
use App\Helpers\ResponseWrapper;
use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(StoreFeedbackRequest $request)
    {
        $validated = $request->validated();
        Feedback::create($validated);
        ActionTrackingHandler::registerAction($request, 'FEEDBACK', 'Feedback sent');
        return ResponseWrapper::successResponse(__('messages.feedback.created'));
    }
}

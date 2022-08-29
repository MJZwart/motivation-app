<?php

namespace App\Http\Controllers;

use App\Helpers\ActionTrackingHandler;
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
        ActionTrackingHandler::handleAction($request, 'FEEDBACK', 'Feedback sent');
        return new JsonResponse(['message' => ['success' => 'Thank you for your feedback. We will contact you if we have any further questions or remarks.']]);
    }
}

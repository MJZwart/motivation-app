<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Http\Requests\StoreBugReportRequest;
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
}

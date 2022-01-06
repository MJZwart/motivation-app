<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Http\BugReportRessource;
use App\Http\Requests\StoreBugReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class BugReportController extends Controller
{
    public function store(StoreBugReportRequest $request): JsonResponse{
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        BugReport::create($validated);

        return new JsonResponse(['message' => ['message' => ['Bug Report successfully created.']]], Response::HTTP_OK);
    }
}

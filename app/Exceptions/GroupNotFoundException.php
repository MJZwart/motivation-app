<?php

namespace App\Exceptions;

use App\Helpers\ResponseWrapper;
use Exception;
use Illuminate\Http\JsonResponse;

class GroupNotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return ResponseWrapper::errorResponse('This group no longer exist.', ['error' => 'GROUP_DELETED']);
    }
}

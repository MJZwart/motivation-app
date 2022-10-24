<?php

namespace App\Exceptions;

use App\Helpers\ResponseWrapper;
use Exception;
use Illuminate\Http\JsonResponse;

class GroupNotAuthorizedException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return ResponseWrapper::errorResponse('You are not authorized to perform this group action.');
    }
}

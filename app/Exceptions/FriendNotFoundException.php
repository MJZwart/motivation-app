<?php

namespace App\Exceptions;

use App\Helpers\ResponseWrapper;
use Exception;
use Illuminate\Http\JsonResponse;

class FriendNotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return ResponseWrapper::errorResponse('This friendship no longer exist. Please reload the page.', ['error' => 'FRIEND_DELETED']);
    }
}

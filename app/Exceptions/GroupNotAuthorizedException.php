<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GroupNotAuthorizedException extends Exception
{
    protected $responseCode = Response::HTTP_BAD_REQUEST;

    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return new JsonResponse(['message' => 'You are not authorized to perform this group action.'], $this->responseCode);
    }
}

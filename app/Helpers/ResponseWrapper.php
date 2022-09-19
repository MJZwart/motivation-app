<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ResponseWrapper
{
    /**
     * Returns a 200 response with message. The message will be picked up by a success toast and the data is wrapped in a 'data' array. 
     * Leave the message blank to leave out the success toast.
     * Leave the return data blank to only send a 200 response in a JsonResponse.
     * @param string $message
     * @param array $returnData
     */
    public static function successResponse(string $message = null, array | AnonymousResourceCollection $returnData = null): JsonResponse
    {
        return ResponseWrapper::constructJsonResponse($message, $returnData, Response::HTTP_OK);
    }

    /**
     * Returns a 422 response with message. The message will automatically be picked up by an error toast.
     * @param string $message
     */
    public static function errorResponse(string $message = null, array | AnonymousResourceCollection $returnData = null): JsonResponse
    {
        return ResponseWrapper::constructJsonResponse($message, $returnData, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function forbiddenResponse(string $message = null): JsonResponse
    {
        return ResponseWrapper::constructJsonResponse($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Constructs a JsonResponse that is to be sent to front end. Messages will be picked up by the toast
     *
     * @param string|null $message
     * @param array|null $returnData
     * @param integer $response
     * @return JsonResponse
     */
    private static function constructJsonResponse(string $message = null, array | AnonymousResourceCollection $returnData = null, int $response): JsonResponse
    {
        $data = null;
        if ($message && !$data)
            $data = ['message' => $message];
        if ($returnData && !$message)
            $data = ['data' => $returnData];
        if ($message && $data)
            $data = ['message' => $message, 'data' => $returnData];
        return new JsonResponse($data, $response);
    }
}

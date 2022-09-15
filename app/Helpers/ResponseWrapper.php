<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
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
    public function successResponse(string $message = null, array $returnData = null): JsonResponse
    {
        return $this->constructJsonResponse($message, 'success', $returnData, Response::HTTP_OK);
    }

    /**
     * Returns a 422 response with message. The message will be picked up by an error toast.
     * @param string $message
     */
    public function errorResponse(string $message = null): JsonResponse
    {
        return $this->constructJsonResponse($message, 'error', null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function constructJsonResponse(string $message = null, string $messageType = 'info', array $returnData = null, int $response) {
        $data = null;
        if ($message && !$data)
            $data = ['message' => [$messageType => $message]];
        if ($returnData && !$message)
            $data = ['data' => $returnData];
        if ($message && $data)
            $data = ['message' => [$messageType => $message], 'data' => $returnData];
        return new JsonResponse($data, $response);
    }
}
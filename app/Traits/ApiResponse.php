<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait ApiResponse
{
    /**
     * Generates a not found response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.not_found'));
    }

    /**
     * Generates a bad request response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.bad_request'));
    }

    /**
     * Generates a not found response for a request
     *
     * @param string $message
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedValidation(string $message, array $errors = []): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.validation_error'), $errors);
    }

    /**
     * Generates an unauthorized response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.unauthorized'));
    }

    /**
     * Generates a method not found response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function methodNotAllowed(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.method_not_found'));
    }

    /**
     * Generates a failed Data Creation response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedDataCreation(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.bad_request'));
    }

    /**
     * Generates a success response for a request
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $message, array $data = []): JsonResponse
    {
        return $this->buildResponse($message, 'success', config('errors.codes.ok'), $data);
    }

    /**
     * Generates a success response for a request
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionSuccess(string $message, array $data = []): JsonResponse
    {
        return $this->buildResponse($message, 'success', config('errors.codes.ok'), $data);
    }

    /**
     * Generates an error response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.conflict'));
    }

    /**
     * Generates a server error response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverError(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.server_error'));
    }

    /**
     * Generates a forbidden response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden(string $message): JsonResponse
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.forbidden'));
    }

    /**
     * Built a response for a request
     *
     * @param string $message
     * @param string $status
     * @param int $statusCode
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function buildResponse(
        string $message,
        string $status,
        int $statusCode,
        array $data = [],
        array $headers = []
    ): JsonResponse {
        $responseData = [
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ];

        if (!empty($data)) {
            $responseData['data'] = $data;
        }

        return new JsonResponse($responseData, $statusCode, $headers);
    }
}

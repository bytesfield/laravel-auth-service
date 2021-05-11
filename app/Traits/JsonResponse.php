<?php

namespace App\Traits;

use \Illuminate\Support\Facades\Response;

trait JsonResponse
{
    /**
     * Generates a not found response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function notFound(string $message)
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.not_found'));
    }

    /**
     * Generates a not found response for a request
     *
     * @param string $message
     * @param array $errors
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function failedValidation(string $message, array $errors = [])
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.validation_error'), $errors);
    }

    /**
     * Generates an unauthorized response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function unauthorized(string $message)
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.unauthorized'));
    }

    /**
     * Generates a method not found response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function methodNotAllowed(string $message)
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.method_not_found'));
    }

    /**
     * Generates a failed Data Creation response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function failedDataCreation(string $message)
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.bad_request'));
    }

    /**
     * Generates a success response for a request
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function success(string $message, array $data = [])
    {
        return $this->buildResponse($message, 'success', config('errors.codes.ok'), $data);
    }

    /**
     * Generates a success response for a request
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function actionSuccess(string $message, array $data = [])
    {
        return $this->buildResponse($message, 'success', config('errors.codes.ok'), $data);
    }

    /**
     * Generates an error response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function error(string $message)
    {
        return $this->buildResponse($message, 'failed', config('errors.codes.conflict'));
    }

    /**
     * Generates a forbidden response for a request
     *
     * @param string $message
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function forbidden(string $message)
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
     * @return \Illuminate\Support\Facades\Response
     */
    private function buildResponse(
        string $message,
        string $status,
        int $statusCode,
        array $data = [],
        array $headers = []
    ) {
        $responseData = [
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ];

        if (!empty($data)) {
            $responseData['data'] = $data;
        }

        return Response::json($responseData, $statusCode, $headers);
    }
}

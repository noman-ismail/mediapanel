<?php

namespace NomanIsmail\MediaPanel\Services;

/**
 * Base Service Class
 * Provides common service methods for error handling and response formatting
 */
abstract class BaseService
{
    /**
     * Handle exceptions and return formatted error response.
     *
     * @param \Throwable $e
     * @param string $defaultMessage
     * @return array
     */
    protected function handleException(\Throwable $e, string $defaultMessage = 'An error occurred'): array
    {
        \Illuminate\Support\Facades\Log::error($defaultMessage . ': ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
        ]);

        return [
            'success' => false,
            'message' => $defaultMessage . ': ' . $e->getMessage(),
            'data' => null,
        ];
    }

    /**
     * Return success response.
     *
     * @param mixed $data
     * @param string $message
     * @return array
     */
    protected function success($data = null, string $message = 'Operation successful'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Return error response.
     *
     * @param string $message
     * @param mixed $data
     * @return array
     */
    protected function error(string $message, $data = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
        ];
    }
}


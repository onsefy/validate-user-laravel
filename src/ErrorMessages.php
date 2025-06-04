<?php

namespace OnSefy\Laravel;

class ErrorMessages
{
    public const DEFAULT = 'onsefy';
    public const DISPOSABLE = 'onsefy_disposable';

    /**
     * Translates a known message key using Laravel’s localization.
     */
    public static function get(string $key, string $attribute): string
    {
        return trans("onsefy::validation.{$key}", ['attribute' => $attribute]);
    }

    /**
     * Maps known error codes to translation keys or default ones.
     */
    public static function forErrorCode(?int $errorCode, string $attribute): string
    {
        $key = match ($errorCode) {
            default => self::DEFAULT,
        };

        return self::get($key, $attribute);
    }

    /**
     * Extracts and returns a message for a given field from the API response.
     */
    public static function fromParameterError(array $parameters, string $attribute): ?string
    {
        if (!isset($parameters[$attribute])) {
            return null;
        }

        $param = $parameters[$attribute];

        // Use explicit message from API if present
        if (!empty($param['message'])) {
            return $param['message'];
        }

        // Fallback to error_code mapping
        if (!empty($param['error_code'])) {
            return self::forErrorCode((int)$param['error_code'], $attribute);
        }

        return null;
    }

    /**
     * Optionally: Pull multiple error messages at once for known fields
     */
    public static function collectErrors(array $parameters, array $fields = ['email', 'ip', 'phone', 'user_agent', 'name']): array
    {
        $errors = [];

        foreach ($fields as $field) {
            $message = self::fromParameterError($parameters, $field);
            if ($message !== null) {
                $errors[$field] = $message;
            }
        }

        return $errors;
    }
}

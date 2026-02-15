<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Support;

/**
 * Política de sanitização para remover/ofuscar dados sensíveis do contexto.
 */
class SanitizationPolicy
{
    private const REDACTED = '[REDACTED]';

    private const MAX_STRING_LENGTH = 1000;

    private const MAX_ARRAY_DEPTH = 5;

    /**
     * Chaves sensíveis que devem ser removidas/ofuscadas.
     */
    private array $sensitiveKeys = [
        'password',
        'token',
        'api_key',
        'secret',
        'authorization',
        'credit_card',
        'ssn',
        'cpf',
    ];

    public function __construct(array $additionalSensitiveKeys = [])
    {
        $this->sensitiveKeys = array_merge(
            $this->sensitiveKeys,
            $additionalSensitiveKeys
        );
    }

    /**
     * Sanitiza o contexto removendo dados sensíveis.
     */
    public function sanitize(array $context, int $depth = 0): array
    {
        if ($depth >= self::MAX_ARRAY_DEPTH) {
            return ['[MAX_DEPTH_EXCEEDED]'];
        }

        $sanitized = [];

        foreach ($context as $key => $value) {
            if ($this->isSensitiveKey($key)) {
                $sanitized[$key] = self::REDACTED;

                continue;
            }

            $sanitized[$key] = match (true) {
                is_array($value) => $this->sanitize($value, $depth + 1),
                is_string($value) => $this->sanitizeString($value),
                is_object($value) => $this->sanitizeObject($value, $depth),
                default => $value,
            };
        }

        return $sanitized;
    }

    private function isSensitiveKey(string $key): bool
    {
        $lowerKey = strtolower($key);

        foreach ($this->sensitiveKeys as $sensitive) {
            if (str_contains($lowerKey, strtolower($sensitive))) {
                return true;
            }
        }

        return false;
    }

    private function sanitizeString(string $value): string
    {
        if (strlen($value) > self::MAX_STRING_LENGTH) {
            return substr($value, 0, self::MAX_STRING_LENGTH).'...[TRUNCATED]';
        }

        return $value;
    }

    private function sanitizeObject(object $value, int $depth): array|string
    {
        if (method_exists($value, 'toArray')) {
            return $this->sanitize($value->toArray(), $depth + 1);
        }

        return '[OBJECT:'.get_class($value).']';
    }
}

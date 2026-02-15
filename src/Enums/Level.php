<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Enums;

/**
 * Níveis de severidade para notificações/eventos.
 */
enum Level: string
{
    case Debug = 'debug';
    case Info = 'info';
    case Notice = 'notice';
    case Warning = 'warning';
    case Error = 'error';
    case Critical = 'critical';
    case Alert = 'alert';
    case Emergency = 'emergency';

    /**
     * Retorna todos os valores como array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Verifica se o nível é considerado severo.
     */
    public function isSevere(): bool
    {
        return in_array($this, [
            self::Error,
            self::Critical,
            self::Alert,
            self::Emergency,
        ], true);
    }

    /**
     * Retorna a prioridade numérica (0 = mais grave).
     */
    public function priority(): int
    {
        return match ($this) {
            self::Emergency => 0,
            self::Alert => 1,
            self::Critical => 2,
            self::Error => 3,
            self::Warning => 4,
            self::Notice => 5,
            self::Info => 6,
            self::Debug => 7,
        };
    }
}

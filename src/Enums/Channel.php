<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Enums;

/**
 * Canais temáticos para categorizar notificações/eventos.
 */
enum Channel: string
{
    case General = 'general';
    case Security = 'security';
    case Performance = 'performance';
    case Business = 'business';
    case System = 'system';
    case Audit = 'audit';

    /**
     * Retorna todos os valores como array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Indica se o canal requer auditoria reforçada.
     */
    public function requiresAudit(): bool
    {
        return in_array($this, [
            self::Security,
            self::Audit,
        ], true);
    }
}

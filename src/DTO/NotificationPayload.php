<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\DTO;

use OrthancTower\Contracts\Enums\Channel;
use OrthancTower\Contracts\Enums\Level;
use OrthancTower\Contracts\Exceptions\InvalidPayloadException;

/**
 * DTO imutável para representar uma notificação/evento.
 */
readonly class NotificationPayload
{
    public function __construct(
        public Channel $channel,
        public Level $level,
        public string $message,
        public array $context = [],
        public ?string $requestId = null,
        public ?string $appId = null,
        public ?int $timestamp = null,
    ) {
        if (empty(trim($this->message))) {
            throw new InvalidPayloadException('Message cannot be empty');
        }

        if (strlen($this->message) > 65535) {
            throw new InvalidPayloadException('Message exceeds maximum length (65535 chars)');
        }
    }

    /**
     * Cria a partir de array (útil para deserialização).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            channel: Channel::from($data['channel'] ?? throw new InvalidPayloadException('Channel is required')),
            level: Level::from($data['level'] ?? throw new InvalidPayloadException('Level is required')),
            message: $data['message'] ?? throw new InvalidPayloadException('Message is required'),
            context: $data['context'] ?? [],
            requestId: $data['request_id'] ?? null,
            appId: $data['app_id'] ?? null,
            timestamp: $data['timestamp'] ?? time(),
        );
    }

    /**
     * Converte para array (útil para serialização).
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->channel->value,
            'level' => $this->level->value,
            'message' => $this->message,
            'context' => $this->context,
            'request_id' => $this->requestId,
            'app_id' => $this->appId,
            'timestamp' => $this->timestamp ?? time(),
        ];
    }

    /**
     * Converte para JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Tests\Unit;

use OrthancTower\Contracts\DTO\NotificationPayload;
use OrthancTower\Contracts\Enums\Channel;
use OrthancTower\Contracts\Enums\Level;
use OrthancTower\Contracts\Exceptions\InvalidPayloadException;
use PHPUnit\Framework\TestCase;

class DTOTest extends TestCase
{
    public function test_creates_payload_from_array(): void
    {
        $payload = NotificationPayload::fromArray([
            'channel' => 'general',
            'level' => 'info',
            'message' => 'Test message',
            'context' => ['key' => 'value'],
        ]);

        $this->assertEquals(Channel::General, $payload->channel);
        $this->assertEquals(Level::Info, $payload->level);
        $this->assertEquals('Test message', $payload->message);
    }

    public function test_throws_exception_on_empty_message(): void
    {
        $this->expectException(InvalidPayloadException::class);

        new NotificationPayload(
            channel: Channel::General,
            level: Level::Info,
            message: '',
        );
    }

    public function test_converts_to_array(): void
    {
        $payload = new NotificationPayload(
            channel: Channel::Security,
            level: Level::Critical,
            message: 'Alert',
        );

        $array = $payload->toArray();

        $this->assertEquals('security', $array['channel']);
        $this->assertEquals('critical', $array['level']);
    }
}

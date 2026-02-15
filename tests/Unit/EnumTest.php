<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Tests\Unit;

use OrthancTower\Contracts\Enums\Channel;
use OrthancTower\Contracts\Enums\Level;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test_level_values(): void
    {
        $this->assertContains('critical', Level::values());
        $this->assertContains('info', Level::values());
    }

    public function test_level_is_severe(): void
    {
        $this->assertTrue(Level::Critical->isSevere());
        $this->assertFalse(Level::Info->isSevere());
    }

    public function test_channel_requires_audit(): void
    {
        $this->assertTrue(Channel::Security->requiresAudit());
        $this->assertFalse(Channel::General->requiresAudit());
    }
}

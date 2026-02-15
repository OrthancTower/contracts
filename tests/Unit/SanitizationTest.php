<?php

declare(strict_types=1);

namespace OrthancTower\Contracts\Tests\Unit;

use OrthancTower\Contracts\Support\SanitizationPolicy;
use PHPUnit\Framework\TestCase;

class SanitizationTest extends TestCase
{
    private SanitizationPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new SanitizationPolicy;
    }

    public function test_redacts_sensitive_keys(): void
    {
        $context = [
            'username' => 'john',
            'password' => 'secret123',
            'api_key' => 'xyz789',
        ];

        $sanitized = $this->policy->sanitize($context);

        $this->assertEquals('john', $sanitized['username']);
        $this->assertEquals('[REDACTED]', $sanitized['password']);
        $this->assertEquals('[REDACTED]', $sanitized['api_key']);
    }

    public function test_truncates_long_strings(): void
    {
        $longString = str_repeat('a', 1500);
        $context = ['data' => $longString];

        $sanitized = $this->policy->sanitize($context);

        $this->assertStringContainsString('[TRUNCATED]', $sanitized['data']);
    }
}

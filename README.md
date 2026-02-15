# 📦 Orthanc Contracts

> Shared type-safe contracts for Orthanc Tower ecosystem

## Installation

```bash
composer require orthanc-tower/orthanc-contracts:@dev
```

## Usage

### Enums

```php
use OrthancTower\Contracts\Enums\{Channel, Level};

$channel = Channel::Security;
$level = Level::Critical;

if ($level->isSevere()) {
    // Handle severe notification
}
```

### DTO

```php
use OrthancTower\Contracts\DTO\NotificationPayload;

$payload = NotificationPayload::fromArray([
    'channel' => 'general',
    'level' => 'info',
    'message' => 'User logged in',
    'context' => ['user_id' => 123],
]);

$json = $payload->toJson();
```

### Sanitization

```php
use OrthancTower\Contracts\Support\SanitizationPolicy;

$policy = new SanitizationPolicy(['custom_secret']);
$sanitized = $policy->sanitize([
    'password' => 'secret',
    'data' => 'public',
]);
// ['password' => '[REDACTED]', 'data' => 'public']
```

### Testing

```bash
composer test
```

## License

MIT

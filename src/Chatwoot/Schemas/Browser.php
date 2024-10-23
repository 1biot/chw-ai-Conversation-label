<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class Browser implements Schema
{
    use SchemaGenerator;
    public readonly string $device_name;
    public readonly string $browser_name;
    public readonly string $platform_name;
    public readonly string $browser_version;
    public readonly string $platform_version;
}

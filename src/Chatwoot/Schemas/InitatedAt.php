<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class InitatedAt implements Schema
{
    use SchemaGenerator;
    public readonly string $timestamp;
}

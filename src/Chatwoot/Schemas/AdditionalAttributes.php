<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class AdditionalAttributes implements Schema
{
    use SchemaGenerator;
    public readonly Browser $browser;
    public readonly string $referer;
    public readonly InitatedAt $initiated_at;
}

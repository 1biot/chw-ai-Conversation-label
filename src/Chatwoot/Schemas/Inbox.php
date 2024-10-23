<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class Inbox implements Schema
{
    use SchemaGenerator;

    public readonly int $id;
    public readonly string $name;
}

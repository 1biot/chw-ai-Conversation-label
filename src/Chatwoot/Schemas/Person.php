<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class Person implements Schema
{
    use SchemaGenerator;

    public readonly int $id;
    public readonly string $name;
    public string $type;
}

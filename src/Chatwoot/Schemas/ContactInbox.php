<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class ContactInbox implements Schema
{
    use SchemaGenerator;

    public readonly int $id;
    public readonly int $contact_id;
    public readonly int $inbox_id;
    public readonly string $source_id;
    public readonly string $created_at;
    public readonly string $updated_at;
    public readonly bool $hmac_verified;
}

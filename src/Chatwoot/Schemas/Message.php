<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class Message implements Schema
{
    use SchemaGenerator;

    public readonly int $id;
    public readonly string $content;
    public readonly string $message_type;
    public readonly string $created_at;
    public bool $private;
    public ?string $source_id = null;
    public readonly string $content_type;
    public readonly object $content_attributes;
    public readonly Person $sender;
    public readonly Account $account;
    public ?Inbox $inbox;
}

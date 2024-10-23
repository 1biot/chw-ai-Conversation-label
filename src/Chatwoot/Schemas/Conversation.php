<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class Conversation implements Schema
{
    use SchemaGenerator;
    public readonly AdditionalAttributes $additional_attributes;
    public readonly bool $can_reply;
    public readonly string $channel;
    public readonly int $id;
    public readonly int $inbox_id;
    public readonly ContactInbox $contact_inbox;

    /** @var array<\Chatwoot\Schemas\Message> */
    public readonly array $messages;

    public readonly ConversationMeta $meta;
    public readonly string $status;
    public readonly int $unread_count;
    public readonly int $agent_last_seen_at;
    public readonly int $contact_last_seen_at;
    public readonly int $timestamp;
    public readonly int $account_id;
}

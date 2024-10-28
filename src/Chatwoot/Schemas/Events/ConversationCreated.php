<?php

namespace Chatwoot\Schemas\Events;

use Chatwoot\Schemas\Models\AdditionalAttributes;
use Chatwoot\Schemas\Models\ContactInbox;
use Chatwoot\Schemas\Models\Event;
use Chatwoot\Schemas\Models\Message;
use Chatwoot\Schemas\Models\Meta;
use DateTime;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class ConversationCreated implements ISchema
{
    public string $event;
    public AdditionalAttributes $additional_attributes;
    public int $agent_last_seen_at;
    public bool $can_reply;
    public string $channel;
    public ContactInbox $contact_inbox;
    public int $contact_last_seen_at;
    public int $created_at;
    public \stdClass $custom_attributes;
    public int $id;
    public ?DateTime $first_reply_created_at;
    public int $inbox_id;
    /** @var array<string> $labels */
    public array $labels;
    public int $last_activity_at;

    /** @var Message[] $messages */
    public array $messages;
    public Meta $meta;
    public ?int $priority;
    public ?DateTime $snoozed_until;
    public string $status;
    public int $timestamp;
    public int $unread_count;
    public int $waiting_since;

    public function getInitialMessage(): ?Message
    {
        return $this->messages[0] ?? null;
    }

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'additional_attributes' => AdditionalAttributes::getSchema(),
            'agent_last_seen_at' => Expect::int(),
            'can_reply' => Expect::bool(),
            'channel' => Expect::string(),
            'contact_inbox' => ContactInbox::getSchema(),
            'contact_last_seen_at' => Expect::int(),
            'created_at' => Expect::int(),
            'custom_attributes' => Expect::structure([]),
            'event' => Event::getSchema(),
            'first_reply_created_at' => Expect::anyOf(Expect::null(), Expect::string()->castTo(DateTime::class)),
            'id' => Expect::int(),
            'inbox_id' => Expect::int(),
            'labels' => Expect::arrayOf(Expect::string()),
            'last_activity_at' => Expect::int(),
            'messages' => Expect::arrayOf(Message::getSchema()),
            'meta' => Meta::getSchema(),
            'priority' => Expect::int()->nullable(),
            'snoozed_until' => Expect::anyOf(Expect::null(), Expect::string()->castTo(DateTime::class)),
            'status' => Expect::string(),
            'timestamp' => Expect::int(),
            'unread_count' => Expect::int(),
            'waiting_since' => Expect::int(),
        ])->castTo(self::class);
    }
}

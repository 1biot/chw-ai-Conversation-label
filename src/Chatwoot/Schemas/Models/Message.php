<?php

namespace Chatwoot\Schemas\Models;

use DateTime;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Message implements ISchema
{
    public int $account_id;
    public \stdClass $additional_attributes;
    public string $content;
    public ContentAttributes $content_attributes;
    public string $content_type;
    public MessageConversation $conversation;
    public int $conversation_id;
    public int $created_at;
    public \stdClass $external_source_ids;
    public int $id;
    public int $inbox_id;
    public int $message_type;
    public bool $private;
    public string $processed_message_content;
    public Sender $sender;
    public int $sender_id;
    public string $sender_type;
    public \stdClass $sentiment;
    public string $source_id;
    public string $status;
    public ?DateTime $updated_at;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'account_id' => Expect::int(),
            'additional_attributes' => Expect::structure([]),
            'content' => Expect::string(),
            'content_attributes' => ContentAttributes::getSchema(),
            'content_type' => Expect::string(),
            'conversation' => MessageConversation::getSchema(),
            'conversation_id' => Expect::int(),
            'created_at' => Expect::int(),
            'external_source_ids' => Expect::structure([]),
            'id' => Expect::int(),
            'inbox_id' => Expect::int(),
            'message_type' => Expect::int(),
            'private' => Expect::bool(),
            'processed_message_content' => Expect::string(),
            'sender' => Sender::getSchema(),
            'sender_id' => Expect::int(),
            'sender_type' => Expect::string(),
            'sentiment' => Expect::structure([]),
            'source_id' => Expect::string(),
            'status' => Expect::string(),
            'updated_at' => Expect::anyOf(Expect::null(), Expect::string()->castTo(DateTime::class)),
        ])->castTo(self::class);
    }
}

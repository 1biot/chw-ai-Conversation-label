<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class MessageConversation implements ISchema
{
    public int $assignee_id;
    public MessageContactInbox $contact_inbox;
    public int $last_activity_at;
    public int $unread_count;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'assignee_id' => Expect::int(),
            'contact_inbox' => MessageContactInbox::getSchema(),
            'last_activity_at' => Expect::int(),
            'unread_count' => Expect::int(),
        ])->castTo(self::class);
    }
}

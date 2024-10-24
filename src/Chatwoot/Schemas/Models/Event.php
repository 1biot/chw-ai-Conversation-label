<?php

namespace Chatwoot\Schemas\Models;

use \Chatwoot\Enums\Event as ChatwootEvent;
use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class Event implements ISchema
{
    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::anyOf(
            ChatwootEvent::ConversationCreated->value,
            ChatwootEvent::ConversationUpdated->value,
            ChatwootEvent::ConversationStatusChanged->value,
            ChatwootEvent::MessageCreated->value,
            ChatwootEvent::MessageUpdated->value,
            ChatwootEvent::WebwidgetTriggered->value
        );
    }
}

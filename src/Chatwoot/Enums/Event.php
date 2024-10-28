<?php

namespace Chatwoot\Enums;

use Chatwoot\Schemas\Events\ConversationCreated;
use Nette\Schema\Schema;

enum Event: string
{
    case ConversationCreated = 'conversation_created';
    case ConversationUpdated = 'conversation_updated';
    case ConversationStatusChanged = 'conversation_status_changed';
    case MessageCreated = 'message_created';
    case MessageUpdated = 'message_updated';
    case WebwidgetTriggered = 'webwidget_triggered';

    /**
     * @throws \Exception
     */
    public static function getSchema(string $eventName): Schema
    {
        $event = Event::tryFrom($eventName);
        if ($event === null) {
            throw new \Exception(sprintf('Disallowed schema for %s event', $eventName));
        }

        return match ($event) {
            Event::ConversationCreated => ConversationCreated::getSchema(),
            default => throw new \Exception(sprintf('Unsupported "%s" schema', $event->name)),
        };
    }
}

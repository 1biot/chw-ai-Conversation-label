<?php

namespace Chatwoot\Enums;

enum Event: string
{
    case ConversationCreated = 'conversation_created';
    case ConversationUpdated = 'conversation_updated';
    case ConversationStatusChanged = 'conversation_status_changed';
    case MessageCreated = 'message_created';
    case MessageUpdated = 'message_updated';
    case WebwidgetTriggered = 'webwidget_triggered';
}

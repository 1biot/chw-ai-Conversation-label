<?php

namespace Chatwoot\Schemas;

use Schema\Schema;
use Schema\SchemaGenerator;

class ConversationMeta implements Schema
{
    use SchemaGenerator;
    public readonly Contact $sender;
    public readonly User $assignee;
}

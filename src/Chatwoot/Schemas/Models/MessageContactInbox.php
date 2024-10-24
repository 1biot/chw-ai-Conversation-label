<?php

namespace Chatwoot\Schemas\Models;

use DateTime;
use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class MessageContactInbox implements ISchema
{
    public string $source_id;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'source_id' => Expect::string()
        ])->castTo(self::class);
    }
}

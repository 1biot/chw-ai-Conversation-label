<?php

namespace Chatwoot\Schemas\Models;

use DateTime;
use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class ContactInbox implements ISchema
{
    public int $contact_id;
    public DateTime $created_at;
    public bool $hmac_verified;
    public int $id;
    public int $inbox_id;
    public string $pubsub_token;
    public string $source_id;
    public DateTime $updated_at;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'contact_id' => Expect::int(),
            'created_at' => Expect::string()->castTo(DateTime::class),
            'hmac_verified' => Expect::bool(),
            'id' => Expect::int(),
            'inbox_id' => Expect::int(),
            'pubsub_token' => Expect::string(),
            'source_id' => Expect::string(),
            'updated_at' => Expect::string()->castTo(DateTime::class),
        ])->castTo(self::class);
    }
}

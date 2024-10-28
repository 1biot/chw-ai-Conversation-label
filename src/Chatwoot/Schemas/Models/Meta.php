<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Meta implements ISchema
{
    public Assignee $assignee;
    public bool $hmac_verified;
    public Sender $sender;
    public ?string $team;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'assignee' => Assignee::getSchema(),
            'hmac_verified' => Expect::bool(),
            'sender' => Sender::getSchema(),
            'team' => Expect::anyOf(Expect::null(), Expect::string()),
        ])->castTo(self::class);
    }
}

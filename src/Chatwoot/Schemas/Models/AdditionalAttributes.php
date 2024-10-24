<?php

namespace Chatwoot\Schemas\Models;

use DateTimeImmutable;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class AdditionalAttributes implements ISchema
{
    public ?string $in_reply_to;
    public InitiatedAt $initiated_at;
    public string $mail_subject;
    public string $source;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'in_reply_to' => Expect::anyOf(Expect::null(), Expect::string()),
            'initiated_at' => InitiatedAt::getSchema(),
            'mail_subject' => Expect::string(),
            'source' => Expect::string(),
        ])->castTo(self::class);
    }
}

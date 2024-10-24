<?php

namespace Chatwoot\Schemas\Models;

use DateTimeImmutable;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class InitiatedAt implements ISchema
{
    public DateTimeImmutable $timestamp;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'timestamp' => Expect::string()->castTo(DateTimeImmutable::class),
        ])->castTo(self::class);
    }
}

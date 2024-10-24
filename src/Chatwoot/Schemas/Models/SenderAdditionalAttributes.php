<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class SenderAdditionalAttributes implements ISchema
{
    public string $source_id;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'source_id' => Expect::string(),
        ])->castTo(self::class);
    }
}

<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class Content implements ISchema
{
    public string $full;
    public string $quoted;
    public string $reply;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'full' => Expect::string(),
            'quoted' => Expect::string(),
            'reply' => Expect::string(),
        ])->castTo(self::class);
    }
}

<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Assignee implements ISchema
{
    public ?string $availability_status;
    public string $available_name;
    public string $avatar_url;
    public int $id;
    public string $name;
    public string $thumbnail;
    public string $type;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'availability_status' => Expect::anyOf(Expect::null(), Expect::string()),
            'available_name' => Expect::string(),
            'avatar_url' => Expect::string(),
            'id' => Expect::int(),
            'name' => Expect::string(),
            'thumbnail' => Expect::string(),
            'type' => Expect::string(),
        ])->castTo(self::class);
    }
}

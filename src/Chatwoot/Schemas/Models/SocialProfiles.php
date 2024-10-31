<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class SocialProfiles implements ISchema
{
    public string $facebook;
    public string $github;
    public string $instagram;
    public string $linkedin;
    public string $twitter;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'facebook' => Expect::string(),
            'github' => Expect::string(),
            'instagram' => Expect::string(),
            'linkedin' => Expect::string(),
            'twitter' => Expect::string(),
        ])->castTo(self::class);
    }
}

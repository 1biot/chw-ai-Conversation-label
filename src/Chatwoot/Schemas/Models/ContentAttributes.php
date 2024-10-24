<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class ContentAttributes implements ISchema
{
    public ?string $bcc_email;
    public ?string $cc_email;
    public Email $email;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'bcc_email' => Expect::anyOf(Expect::null(), Expect::string()),
            'cc_email' => Expect::anyOf(Expect::null(), Expect::string()),
            'email' => Email::getSchema(),
        ])->castTo(self::class);
    }
}

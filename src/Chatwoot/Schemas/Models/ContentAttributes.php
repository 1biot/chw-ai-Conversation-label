<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Schema\Schema as ISchema;

class ContentAttributes implements ISchema
{
    /** @var null|string|array<string> $bcc_email */
    public null|array|string $bcc_email;
    /** @var null|string|array<string> $cc_email */
    public null|array|string $cc_email;
    public Email $email;

    public static function getSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'bcc_email' => Expect::anyOf(Expect::null(), Expect::string(), Expect::arrayOf(Expect::string())),
            'cc_email' => Expect::anyOf(Expect::null(), Expect::string(), Expect::arrayOf(Expect::string())),
            'email' => Email::getSchema(),
        ])->castTo(self::class);
    }
}

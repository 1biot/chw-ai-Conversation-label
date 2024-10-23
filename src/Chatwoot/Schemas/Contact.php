<?php

namespace Chatwoot\Schemas;

use Schema\SchemaGenerator;

class Contact extends Person
{
    public readonly string $avatar;
    public readonly Account $account;
}

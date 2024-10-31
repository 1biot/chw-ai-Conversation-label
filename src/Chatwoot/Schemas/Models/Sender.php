<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Sender implements ISchema
{
    public SenderAdditionalAttributes $additional_attributes;
    public mixed $custom_attributes;
    public string $email;
    public int $id;
    public ?string $identifier;
    public string $name;
    public ?string $phone_number;
    public string $thumbnail;
    public string $type;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'additional_attributes' => SenderAdditionalAttributes::getSchema(),
            'custom_attributes' => Expect::mixed(),
            'email' => Expect::string(),
            'id' => Expect::int(),
            'identifier' => Expect::type('null|string'),
            'name' => Expect::string(),
            'phone_number' => Expect::type('null|string'),
            'thumbnail' => Expect::string(),
            'type' => Expect::string(),
        ])->castTo(self::class);
    }
}

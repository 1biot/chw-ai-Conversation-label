<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class SenderAdditionalAttributes implements ISchema
{
    public ?string $city;
    public ?string $company_name;
    public ?string $country;
    public ?string $country_code;
    public ?string $description;
    public ?SocialProfiles $social_profiles;
    public string $source_id;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'city' => Expect::type('null|string'),
            'company_name' => Expect::type('null|string'),
            'country' => Expect::type('null|string'),
            'country_code' => Expect::type('null|string'),
            'description' => Expect::type('null|string'),
            'social_profiles' => Expect::anyOf(Expect::null(), SocialProfiles::getSchema()),
            'source_id' => Expect::type('null|string'),
        ])->castTo(self::class);
    }
}

<?php

namespace Chatwoot\Schemas\Models;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Attachment implements ISchema
{
    public int $account_id;
    public string $data_url;
    public ?string $extension;
    public int $file_size;
    public string $file_type;
    public int $height;
    public int $id;
    public int $message_id;
    public string $thumb_url;
    public int $width;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'account_id' => Expect::int(),
            'data_url' => Expect::string(),
            'extension' => Expect::type('null|string'),
            'file_size' => Expect::int(),
            'file_type' => Expect::string(),
            'height' => Expect::int(),
            'id' => Expect::int(),
            'message_id' => Expect::int(),
            'thumb_url' => Expect::string(),
            'width' => Expect::int(),
        ])->castTo(self::class);
    }
}

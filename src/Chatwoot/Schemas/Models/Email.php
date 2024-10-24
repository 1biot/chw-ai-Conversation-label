<?php

namespace Chatwoot\Schemas\Models;

use DateTime;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Schema\Schema as ISchema;

class Email implements ISchema
{
    public ?string $bcc;
    public ?string $cc;
    public string $content_type;
    public DateTime $date;
    /** @var string[] $from */
    public array $from;
    public Content $html_content;
    public ?string $in_reply_to;
    public string $message_id;
    public bool $multipart;
    public int $number_of_attachments;
    public string $subject;
    public Content $text_content;
    /** @var string[] $to */
    public array $to;

    public static function getSchema(): Schema
    {
        return Expect::structure([
            'bcc' => Expect::anyOf(Expect::null(), Expect::string()),
            'cc' => Expect::anyOf(Expect::null(), Expect::string()),
            'content_type' => Expect::string(),
            'date' => Expect::string()->castTo(DateTime::class),
            'from' => Expect::arrayOf(Expect::string()),
            'html_content' => Content::getSchema(),
            'in_reply_to' => Expect::anyOf(Expect::null(), Expect::string()),
            'message_id' => Expect::string(),
            'multipart' => Expect::bool(),
            'number_of_attachments' => Expect::int(),
            'subject' => Expect::string(),
            'text_content' => Content::getSchema(),
            'to' => Expect::arrayOf(Expect::string()),
        ])->castTo(self::class);
    }
}

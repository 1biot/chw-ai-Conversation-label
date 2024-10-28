<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

function isDev(): bool {
    return _env('APP_ENV', 'production') === 'development';
}
function getLogDir(): string
{
    $logDir = __DIR__ . '/../log';
    if (!is_dir($logDir)) {
        mkdir($logDir);
    }
    return realpath($logDir);
}

use Dotenv\Exception\InvalidEncodingException;
use Dotenv\Exception\InvalidFileException;
use Leaf\Http\Status;

if (isDev()) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->safeLoad();
        $dotenv->required(
            ['OPENAI_API_KEY', 'OPENAI_ORG', 'OPENAI_ASSISTANT_ID', 'CHATWOOT_API_ACCESS_TOKEN', 'CHATWOOT_API_URL']
        );
    } catch (InvalidEncodingException|InvalidFileException $e) {
        response()->json([], Status::HTTP_INTERNAL_SERVER_ERROR, true);
    }
}

$app = new App\ChatwootListener([
    'debug' => isDev()
]);

return $app;

<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

const LOG_DIR = __DIR__ . '/../log';

use Dotenv\Exception\InvalidEncodingException;
use Dotenv\Exception\InvalidFileException;
use Leaf\Http\Status;

if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR);
}

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
    $dotenv->required(
        ['OPENAI_API_KEY', 'OPENAI_ORG', 'OPENAI_ASSISTANT_ID', 'CHATWOOT_API_ACCESS_TOKEN', 'CHATWOOT_API_URL']
    );
} catch (InvalidEncodingException|InvalidFileException $e) {
    response()->json([], Status::HTTP_INTERNAL_SERVER_ERROR, true);
}

$app = new App\ChatwootListener([
    'debug' => _env('APP_ENV', 'production') === 'development'
]);

return $app;

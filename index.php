<?php
declare(strict_types=1);

use App\ChatwootListener;
use Chatwoot\Enums\Event;
use Chatwoot\Schemas\Events\ConversationCreated;
use Leaf\Http\Request;
use Leaf\Http\Response;
use OpenAi\Assistants\LabelConversion;
use Orhanerday\OpenAi\OpenAi;
use Tracy\Debugger;
use Tracy\ILogger;

$_ENV['APP_ENV'] = 'production';

/** @var ChatwootListener $app */
$app = require_once __DIR__ . '/src/bootstrap.php';

$app->on(Event::ConversationCreated, new class {
    public function __invoke(ConversationCreated $conversation, Request $request, Response $response): void
    {
        try {
            $initialMessage = $conversation->getInitialMessage();
            if ($initialMessage === null || $initialMessage->content === '') {
                throw new InvalidArgumentException('Message content is empty');
            }

            $labels = $this->getLabelFromChatGPT($initialMessage->content);
            if ($labels === null) {
                throw new RuntimeException('Failed to get label from message');
            }

            $client = new Chatwoot\Client($_ENV['CHATWOOT_API_ACCESS_TOKEN'], $_ENV['CHATWOOT_API_URL']);
            if (!$client->addConversationLabel($initialMessage->account_id, $conversation->id, $labels)) {
                throw new RuntimeException('Failed to add label');
            }

            $response->noContent();
        } catch (RuntimeException $e) {
            Tracy\Debugger::log($e->getMessage(), Tracy\ILogger::ERROR);
            response()->json([], Leaf\Http\Status::HTTP_BAD_REQUEST, true);
        }
    }

    private function getLabelFromChatGPT(string $message): ?array
    {
        $openAi = new OpenAi($_ENV['OPENAI_API_KEY']);
        $openAi->setORG($_ENV['OPENAI_ORG']);
        $openAi->setAssistantsBetaVersion('v2');
        $openAi->setTimeout(3);

        try {
            $openAiAssistantId = $_ENV['OPENAI_ASSISTANT_ID'];
            $assistant = new LabelConversion(
                $openAiAssistantId === '' ? null : $openAiAssistantId,
                ["demand", "support", "spam", "offer", "billing"]
            );
            return $assistant($openAi, $message);
        } catch (Exception $e) {
            Debugger::log($e->getMessage(), ILogger::ERROR);
            return null;
        }
    }
});

$app->run();

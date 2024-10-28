<?php
declare(strict_types=1);

use App\ChatwootListener;
use Chatwoot\Enums\Event;
use Chatwoot\Schemas\Events\ConversationCreated;
use Leaf\Http\Request;
use Leaf\Http\Response;
use OpenAi\Assistants\LabelConversion;
use Orhanerday\OpenAi\OpenAi;

/** @var ChatwootListener $app */
$app = require_once __DIR__ . '/src/bootstrap.php';

$app->on(Event::ConversationCreated, new class($app) {

    private OpenAi $openAi;

    public function __construct(private readonly ChatwootListener $app) {
        $this->openAi = new OpenAi($_ENV['OPENAI_API_KEY']);
        $this->openAi->setORG($_ENV['OPENAI_ORG']);
        $this->openAi->setAssistantsBetaVersion('v2');
        $this->openAi->setTimeout(3);
    }

    public function __invoke(Request $request, Response $response): void
    {
        try {
            /** @var ConversationCreated $conversation */
            $conversation = $request->next();
            $initialMessage = $conversation->getInitialMessage();
            if ($initialMessage === null || $initialMessage->content === '') {
                throw new InvalidArgumentException('Message content is empty');
            }

            $labels = $this->getLabelFromChatGPT($initialMessage->content);
            if ($labels === null) {
                throw new RuntimeException('Failed to get label from message');
            }

            if (!$this->app->chatwootClient->addConversationLabel($initialMessage->account_id, $conversation->id, $labels)) {
                throw new RuntimeException('Failed to add label');
            }

            $this->app->logger()->info([
                'conversation_id' => $conversation->id,
                'labels' => $labels,
            ]);
            $response->noContent();
        } catch (RuntimeException $e) {
            $this->app->logger()->error($e);
            response()->json([], Leaf\Http\Status::HTTP_BAD_REQUEST, true);
        }
    }

    private function getLabelFromChatGPT(string $message): ?array
    {
        try {
            $openAiAssistantId = $_ENV['OPENAI_ASSISTANT_ID'];
            $assistant = new LabelConversion(
                $openAiAssistantId === '' ? null : $openAiAssistantId,
                ["demand", "support", "spam", "offer", "billing"]
            );
            return $assistant($this->openAi, $message);
        } catch (Exception $e) {
            $this->app->logger()->error($e);
            return null;
        }
    }
});

$app->run();

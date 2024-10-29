<?php
declare(strict_types=1);

/** @var App\ChatwootListener $app */
$app = require_once __DIR__ . '/src/bootstrap.php';

$myNewWebhookAction = new class($app)
{
    private Orhanerday\OpenAi\OpenAi $openAi;

    public function __construct(private readonly App\ChatwootListener $app)
    {
        $this->openAi = new Orhanerday\OpenAi\OpenAi(_env('OPENAI_API_KEY'), '');
        $this->openAi->setORG(_env('OPENAI_ORG', ''));
        $this->openAi->setAssistantsBetaVersion('v2');
        $this->openAi->setTimeout(3);
    }

    public function __invoke(Leaf\Http\Request $request, Leaf\Http\Response $response): void
    {
        try {
            /** @var Chatwoot\Schemas\Events\ConversationCreated $conversation */
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
        } catch (Exception $e) {
            $this->app->logger()->error($e);
            response()->json([], Leaf\Http\Status::HTTP_BAD_REQUEST, true);
        }
    }

    private function getLabelFromChatGPT(string $message): ?array
    {
        try {
            $openAiAssistantId = _env('OPENAI_ASSISTANT_ID', '');
            $assistant = new OpenAi\Assistants\LabelConversion(
                $openAiAssistantId === '' ? null : $openAiAssistantId,
                ["demand", "support", "spam", "offer", "billing"]
            );
            return $assistant($this->openAi, $message);
        } catch (Exception $e) {
            $this->app->logger()->error($e);
            return null;
        }
    }
};

$app->onConversationCreated($myNewWebhookAction);
$app->run();

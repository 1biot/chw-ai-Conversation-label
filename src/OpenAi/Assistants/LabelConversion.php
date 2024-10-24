<?php

namespace OpenAi\Assistants;

use OpenAi\Enums\Model;
use Orhanerday\OpenAi\OpenAi;

class LabelConversion extends Assistant
{
    private const ASSISTANT_NAME = 'LabelConversion';
    private const ASSISTANT_DESCRIPTION = 'LabelConversion';
    private const ASSISTANT_INSTRUCTION = 'Decide in one word, based on the message, whether the context is %s';

    public function __construct(
        protected ?string $assistantId,
        private readonly ?array $labels = null
    ) {
        parent::__construct(
            $this->assistantId,
            Model::GPT_4O_Mini,
            self::ASSISTANT_NAME,
            self::ASSISTANT_DESCRIPTION,
            sprintf(self::ASSISTANT_INSTRUCTION, implode(', ', $this->labels))
        );
    }

    /**
     * @throws \Exception
     */
    public function __invoke(OpenAi $client, string $message): array
    {
        $this->install($client);
        $labels = $this->run($client, $message);
        $this->uninstall($client);
        return $labels;
    }

    /**
     * @throws \Exception
     */
    private function run(OpenAi $client, string $message): array
    {
        $response = $client->createThreadAndRun(
            [
                'assistant_id' => $this->assistantId,
                'thread' => [
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $message
                        ],
                    ],
                ]
            ]
        );
        $response = @json_decode($response, true);

        $runId = $response['id'];
        $threadId = $response['thread_id'];
        $status = $response['status'];

        while (in_array($status, ['in_progress', 'queued'])) {
            sleep(3);
            $response = $client->retrieveRun($threadId, $runId);
            $response = @json_decode($response, true);
            $status = $response['status'];
        }

        if ($status === 'failed') {
            throw new \Exception($response['last_error']['message'] ?? 'Unknown error');
        } elseif ($status !== 'completed') {
            throw new \Exception('Request not completed and not failed');
        }

        $response = $client->listThreadMessages($threadId);
        $response = @json_decode($response, true);
        $labelString = $response['data'][0]['content'][0]['text']['value'] ?? 'unknown';
        if ($labelString === 'unknown') {
            throw new \Exception('Could not parse response');
        }

        return array_filter(array_map(function (string $label): string {
            return trim($label);
        }, explode(',', $labelString)), function (string $label): bool {
            return $label !== '';
        });
    }
}

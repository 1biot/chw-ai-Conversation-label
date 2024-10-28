<?php

namespace OpenAi\Assistants;

use OpenAi\Enums\Model;
use Orhanerday\OpenAi\OpenAi;

abstract class Assistant
{
    private bool $autoRemove = false;

    /**
     * @param array<string> $tools
     */
    public function __construct(
        protected ?string $assistantId,
        protected Model $model,
        protected string $name,
        protected ?string $description = null,
        protected ?string $instructions = null,
        protected array $tools = []
    ) {}

    /**
     * @throws \Exception
     */
    public function install(OpenAi $client): string
    {
        if ($this->assistantId) {
            return $this->assistantId;
        }

        $assistants = $this->responseToArray($client->listAssistants());
        foreach ($assistants['data'] ?? [] as $assistant) {
            if ($assistant['instructions'] !== $this->instructions) {
                continue;
            }
            break;
        }

        if (!isset($assistant)) {
            $assistant = $this->responseToArray(
                $client->createAssistant([
                    'model' => $this->model,
                    'name' => $this->name,
                    'description' => $this->description,
                    'instructions' => $this->instructions,
                    'tools' => $this->tools,
                ])
            );

            if ($assistant['id'] ?? true) {
                throw new \Exception('Could not instantiate Assistant');
            }

            $this->autoRemove = true;
        }

        $this->assistantId = $assistant['id'];
        return $this->assistantId;
    }

    public function uninstall(OpenAi $client): void
    {
        if ($this->autoRemove && $this->assistantId) {
            $client->deleteAssistant($this->assistantId);
            $this->assistantId = null;
        }
    }

    /**
     * @param string $response
     * @return array<string|int, mixed>
     */
    public function responseToArray(string $response): array
    {
        return @json_decode($response, true);
    }
}

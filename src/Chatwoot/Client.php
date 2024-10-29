<?php

namespace Chatwoot;

use GuzzleHttp;
use Leaf;

class Client
{
    private ?Leaf\Log $logger;

    public function __construct(
        private readonly string $apiAccessToken,
        private readonly string $domain = "https://api.chatwoot.com"
    ) {}

    /**
     * @param array<string> $labels
     */
    public function addConversationLabel(int $accountId, int $conversationId, array $labels = []): bool
    {
        $api = sprintf(
            '%s/api/v1/accounts/%s/conversations/%s/labels',
            $this->domain,
            $accountId,
            $conversationId
        );

        try {
            $client = new GuzzleHttp\Client();
            $response = $client->post($api, $this->assembleRequestOptions(['labels' => $labels]));
            return $response->getStatusCode() === 200;
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            $this->logger?->error($e);
            return false;
        }
    }

    /**
     * @param array<string, array<string, string>> $body
     * @return array<string, string|array<string, string>>
     */
    private function assembleRequestOptions(array $body): array
    {
        return [
            'headers' => [
                'Content-type' => 'application/json; charset=utf-8',
                'api_access_token' => $this->apiAccessToken
            ],
            'body' => json_encode($body)
        ];
    }

    public function setLogger(?Leaf\Log $logger): self
    {
        $this->logger = $logger;
        return $this;
    }
}

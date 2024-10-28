<?php

namespace Chatwoot;

use GuzzleHttp\Exception\GuzzleException;

class Client
{

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

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post($api, [
                'headers' => [
                    'Content-type' => 'application/json; charset=utf-8',
                    'api_access_token' => $this->apiAccessToken
                ],
                'body' => json_encode(['labels' => $labels])
            ]);

            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            return false;
        }
    }
}

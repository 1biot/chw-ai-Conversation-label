<?php

namespace App;

use Chatwoot;
use Leaf;
use Nette;

final class ChatwootListener extends Leaf\App
{
    /**
     * @var array{value-of<Chatwoot\Enums\Event::ConversationCreated>?: callable[]}
     */
    private array $listeners = [];

    public function __construct(array $userSettings = [])
    {
        $userSettings = array_merge($userSettings, [
            'log.enabled' => true,
            'log.dir' => getLogDir(),
            'log.file' => (new \DateTime())->format('Y-m-d') . '_crash_logs.log',
        ]);

        parent::__construct($userSettings);
        $this->cors(
            [
                'origin' => $this->config('debug') ? '*' : _env('CHATWOOT_API_URL'),
                'allowedHeaders' => 'Content-Type'
            ]
        );
        $this->config('debug');
        self::use([$this, 'authMiddleware']);
        self::post('/', ['middleware' => [$this, 'checkEventMiddleware'], [$this, 'actionProcessEvent']]);
        self::get('/', function() {
            $this->response()->json('hello world');
        });

        self::set404([$this, 'action404']);
        self::setErrorHandler([$this, 'errorHandler']);
    }

    public function on(Chatwoot\Enums\Event $event, callable $callback): self
    {
        if (!isset($this->listeners[$event->value])) {
            $this->listeners[$event->value] = [];
        }

        $this->listeners[$event->value][] = $callback;
        return $this;
    }

    protected function authMiddleware(): void
    {
        $token = $this->request()::urlData('token', '');
        if ($token !== $_ENV['AUTH_TOKEN'] ?? '') {
            $this->response()->exit([], Leaf\Http\Status::HTTP_UNAUTHORIZED);
        }
    }

    protected function checkEventMiddleware(): void
    {
        try {
            $rawData = $this->request()::rawData();
            $schema = Chatwoot\Enums\Event::getSchema($rawData['event'] ?? '');
            $processor = new Nette\Schema\Processor;
            /** @var Chatwoot\Schemas\Events\ConversationCreated $conversationCreatedEvent */
            $event = $processor->process($schema, $rawData);
            $this->response()->next($event);
        } catch (\Exception $e) {
            $this->logger()->error($e);
            file_put_contents($this->config('log.dir') . time() . '.req', json_encode($this->request()::rawData()));
            $this->response()->exit([], Leaf\Http\Status::HTTP_BAD_REQUEST);
        }
    }

    protected function actionProcessEvent(): void
    {
        /** @var Chatwoot\Schemas\Events\ConversationCreated $event */
        $event = $this->request()::next();
        $listeners = $this->listeners[$event->event] ?? [];
        foreach ($listeners as $callback) {
            if (is_callable($callback)) {
                call_user_func_array(
                    $callback,
                    [$event, $this->request(), $this->response()]
                );
            }
        }
    }

    protected function action404(): void
    {
        $this->response()->die([], Leaf\Http\Status::HTTP_NOT_FOUND);
    }

    protected function errorHandler(): void
    {
        $this->response()->die([], Leaf\Http\Status::HTTP_INTERNAL_SERVER_ERROR);
    }
}

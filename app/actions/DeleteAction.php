<?php

namespace livetyping\hermitage\app\actions;

use livetyping\hermitage\foundation\bus\commands\DeleteImageCommand;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SimpleBus\Message\Bus\MessageBus;

/**
 * Class DeleteAction
 *
 * @package livetyping\hermitage\app\actions
 */
class DeleteAction
{
    /** @var \SimpleBus\Message\Bus\MessageBus */
    protected $bus;

    /**
     * DeleteAction constructor.
     *
     * @param \SimpleBus\Message\Bus\MessageBus $bus
     */
    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param string                                   $filename
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    public function __invoke(string $filename, Request $request, Response $response): Response
    {
        $command = new DeleteImageCommand($filename);
        $this->bus->handle($command);

        return $response->withStatus(204);
    }
}

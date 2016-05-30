<?php

namespace livetyping\hermitage\app\actions;

use livetyping\hermitage\app\exceptions\BadRequestException;
use livetyping\hermitage\foundation\bus\commands\StoreImageCommand;
use livetyping\hermitage\foundation\Util;
use Psr\Http\Message\ServerRequestInterface as Request;
use SimpleBus\Message\Bus\MessageBus;
use Slim\Http\Response;

/**
 * Class StoreAction
 *
 * @package livetyping\hermitage\app\actions
 */
class StoreAction
{
    /** @var \SimpleBus\Message\Bus\MessageBus */
    protected $bus;

    /**
     * StoreAction constructor.
     *
     * @param \SimpleBus\Message\Bus\MessageBus $bus
     */
    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Slim\Http\Response                      $response
     *
     * @return \Slim\Http\Response
     * @throws \livetyping\hermitage\app\exceptions\BadRequestException
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $mime = (string)current($request->getHeader('Content-Type'));
        $binary = (string)$request->getBody();

        if (empty($mime) || empty($binary) || !in_array($mime, Util::supportedMimeTypes())) {
            throw new BadRequestException('Invalid mime-type or body.');
        }

        $command = new StoreImageCommand($mime, $binary);
        $this->bus->handle($command);

        return $response->withStatus(201)->withJson(['filename' => $command->getPath()]);
    }
}
